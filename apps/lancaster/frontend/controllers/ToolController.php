<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Tool controller
 */
class ToolController extends Controller
{
    public $layout = '@app/views/layouts/tool';
    public $_session;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
//                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionChart()
    {
        $filePath = Yii::$app->view->theme->basePath . '/resources/chart/data.json';
        $fileContent = $this->readFile($filePath);
        return $this->render('chart', [
            'data'=>$fileContent
        ]);
    }

    public function actionGetChart()
    {
        if(Yii::$app->request->isAjax){
            $filePath = Yii::$app->view->theme->basePath . '/resources/chart/data.json';
            $fileContent = $this->readFile($filePath);
            $fileContent = Json::decode($fileContent, true);
            return $this->renderAjax('_partials/chart_view', [
                'data'=>$fileContent
            ]);
        }
    }

    public function actionSaveStep()
    {
        $app = Yii::$app;
        if(empty($this->_session)){
            $this->_session = $app->getSession();
        }
        if($app->request->isPost){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $step = $app->request->get('step');
//            $this->_session[$step] = $app->request->post();
            $this->_session = Yii::$app->session;
            $this->_session->set($step, filter_var($app->request->post('total_project_cost'), FILTER_SANITIZE_NUMBER_INT));

            $pathData = Yii::$app->view->theme->basePath.'\\resources\\chart\\';

            $data_file = 'data.json';
            if($step == 'developmentCostPlan'){
                return [
                    'net_sellable' => filter_var($app->request->post('net_sellable'), FILTER_SANITIZE_NUMBER_INT),
                    'subtotal' => filter_var($app->request->post('subtotal'), FILTER_SANITIZE_NUMBER_INT)
                ];
            }
            else if($step == 'profitMarginCalculation'){
                return [
                    'total_project_cost '=> filter_var($app->request->post('total_project_cost'), FILTER_SANITIZE_NUMBER_INT),
                    'net_sellable'=> filter_var($app->request->post('net_sellable'), FILTER_SANITIZE_NUMBER_INT),
                    'sale_price'=> filter_var($app->request->post('sale_price'), FILTER_SANITIZE_NUMBER_INT),
                ];
            }
            else if($step == 'scenario_1' || $step == "calculation_1"){
                $post = $app->request->post();

                $net_cashflow = [];
                $total_project_cost = array_key_exists("total_project_cost", $post) !== false ? $post["total_project_cost"] : 0;
                $sales_price_w_vat = array_key_exists("sales_price_w_vat", $post) !== false ? $post["sales_price_w_vat"] : 0;
                $net_sellable_area = array_key_exists("net_sellable_area", $post) !== false ? $post["net_sellable_area"] : 0;

                $outgoing_cashflow = 0;
                $cumulative_revenue = 0;

                $incoming_cashflow = 0;
                $accumulative_incoming_cashflow = 0;

                for($i = 1; $i <= $post["counter"]; $i++)
                {
                    $cashflow_percent = 0;
                    $is_cashflow = array_key_exists("T".$i."_cashflow", $post);
                    if($is_cashflow)
                        $cashflow_percent = $post["T".$i."_cashflow"];

                    $sales_percent = 0;
                    $is_sales = array_key_exists("T".$i."_sales", $post);
                    if($is_sales)
                        $sales_percent = $post["T".$i."_sales"];

                    $out_cashflow = (-1 * $cashflow_percent * $total_project_cost)/100;
                    $outgoing_cashflow = $outgoing_cashflow + round($out_cashflow, 0, PHP_ROUND_HALF_UP);

                    $revenue = $sales_percent/100 * $sales_price_w_vat * $net_sellable_area;
                    $cumulative_revenue = $cumulative_revenue + $revenue;

                    $is_payment = array_key_exists("T".$i."_payment", $post);
                    $pay = 0;
                    if($is_payment) {
                        foreach ($post["T" . $i . "_payment"] as $v) {
                            if(array_key_exists($v, $post) !== false )
                                $pay = $pay + $post[$v];
                        }

                        $incoming_cashflow = $pay / 100 * $cumulative_revenue - $accumulative_incoming_cashflow;
                    }
                    $accumulative_incoming_cashflow = $accumulative_incoming_cashflow + $incoming_cashflow;
                    $total = $outgoing_cashflow + $accumulative_incoming_cashflow;
                    $net_cashflow["T".$i] = [0 => round($total, 0, PHP_ROUND_HALF_UP), 1 => $sales_percent, 2 => $cashflow_percent, 3 => $pay > 0 ? $post["T" . $i . "_payment"] : []];
                }

                $scenario_1 = ['scenario_1' => $net_cashflow];
                $data_1 = Json::encode($scenario_1, JSON_NUMERIC_CHECK);

                if ($this->writeFile($pathData . $data_file, $data_1)) {
                    if($step == "calculation_1"){
                        return ['status' => 'Calculation 1 OK', 'file' => $data_file, 'scenario' => $net_cashflow];
                    }
                    else
                        return ['status' => 'Scenario 1 OK', 'file' => $data_file];
                }
                else
                    return ['status' => 'Failed. Cannot return data'];

            }
            else if($step == 'scenario_2' || $step == 'calculation_2'){

                $post  = $app->request->post();

                if(file_exists($pathData.$data_file)) {
                    $file = $this->readFile($pathData.$data_file);
                    $arr_file = Json::decode($file, true);

                    $net_cashflow_2 = [];
                    $total_project_cost = array_key_exists("total_project_cost", $post) !== false ? $post["total_project_cost"] : 0;
                    $sales_price_w_vat = array_key_exists("sales_price_w_vat", $post) !== false ? $post["sales_price_w_vat"] : 0;
                    $net_sellable_area = array_key_exists("net_sellable_area", $post) !== false ? $post["net_sellable_area"] : 0;


                    $outgoing_cashflow = 0;
                    $cumulative_revenue = 0;

                    $incoming_cashflow = 0;
                    $accumulative_incoming_cashflow = 0;

                    for($i = 1; $i <= $post["counter_2"]; $i++)
                    {
                        $cashflow_percent = 0;
                        $is_cashflow = array_key_exists("T".$i."_cashflow", $post);
                        if($is_cashflow)
                            $cashflow_percent = $post["T".$i."_cashflow"];

                        $sales_percent = 0;
                        $is_sales = array_key_exists("T".$i."_sales", $post);
                        if($is_sales)
                            $sales_percent = $post["T".$i."_sales"];

                        $out_cashflow = (-1 * $cashflow_percent * $total_project_cost)/100;
                        $outgoing_cashflow = $outgoing_cashflow + round($out_cashflow, 0, PHP_ROUND_HALF_UP);

                        $revenue = $sales_percent/100 * $sales_price_w_vat * $net_sellable_area;
                        $cumulative_revenue = $cumulative_revenue + $revenue;

                        $pay = 0;
                        $is_payment = array_key_exists("T".$i."_payment", $post);
                        if($is_payment) {
                            foreach ($post["T" . $i . "_payment"] as $v) {
                                if(array_key_exists($v, $post) !== false )
                                    $pay = $pay + $post[$v];
                            }

                            $incoming_cashflow = $pay / 100 * $cumulative_revenue - $accumulative_incoming_cashflow;
                        }

                        $accumulative_incoming_cashflow = $accumulative_incoming_cashflow + $incoming_cashflow;

                        $total = $outgoing_cashflow + $accumulative_incoming_cashflow;
                        $net_cashflow_2["T".$i] = [0 => round($total, 0, PHP_ROUND_HALF_UP), 1 => $sales_percent, 2 => $cashflow_percent, 3 => $pay > 0 ? $post["T" . $i . "_payment"] : []];

                    }

                    $arr_file['scenario_2'] = $net_cashflow_2;
                    $data_2 = Json::encode($arr_file, JSON_NUMERIC_CHECK);
                    $this->writeFile($pathData . $data_file, $data_2);
                    if($step == "calculation_2"){
                        return ['status' => 'Calculation 2 OK', 'file' => $data_2, 'scenario' => $net_cashflow_2];
                    }
                    else
                        return ['status' => 'Scenario 2 OK', 'file' => $data_file];
                } else
                    return ['status' => 'File not found. Run again scenario 1'];
            }
            else{
                return ['total_project_cost'=>filter_var($app->request->post('total_project_cost'), FILTER_SANITIZE_NUMBER_INT)];
            }
        }
    }

//    private function getData(){
//        $values_1 = [
//            ['X'=>'T1', 'Y'=>'270'],
//            ['X'=>'T2', 'Y'=>'342'],
//            ['X'=>'T3', 'Y'=>'373'],
//            ['X'=>'T4', 'Y'=>'389'],
//            ['X'=>'T5', 'Y'=>'534'],
//            ['X'=>'T6', 'Y'=>'547'],
//            ['X'=>'T7', 'Y'=>'560'],
//            ['X'=>'T8', 'Y'=>'580'],
//            ['X'=>'T9', 'Y'=>'651'],
//        ];
//        $values_2 = [
//            ['X'=>'T1', 'Y'=>'270'],
//            ['X'=>'T2', 'Y'=>'342'],
//            ['X'=>'T3', 'Y'=>'373'],
//            ['X'=>'T4', 'Y'=>'318'],
//            ['X'=>'T5', 'Y'=>'456'],
//            ['X'=>'T6', 'Y'=>'462'],
//            ['X'=>'T7', 'Y'=>'468'],
//            ['X'=>'T8', 'Y'=>'480'],
//            ['X'=>'T9', 'Y'=>'544'],
//        ];
//        $data = [
//            'scenario_1' => [
//                'linecolor' => '#5698D3',
//                'title' => 'Scenario 1',
//                'values' => $values_1,
//            ],
//            'scenario_2' => [
//                'linecolor' => '#EE863F',
//                'title' => 'Scenario 2',
//                'values' => $values_2,
//            ],
//        ];
//        return $data;
//    }

    private function writeFile($filePath, $data){
        $handle = fopen($filePath, 'w') or die('Cannot open file:  '.$filePath);
        fwrite($handle, $data);
        fclose($handle);
        return true;
    }

    private function readFile($filePath){

        $handle = fopen($filePath, 'r') or die('Cannot open file:  '.$filePath);
        if(filesize($filePath) > 0) {
            $data = fread($handle, filesize($filePath));
            return $data;
        }
        else return null;

    }
}
