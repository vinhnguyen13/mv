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
            $fileContent = Json::decode($fileContent);
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

            $path = Yii::$app->view->theme->basePath.'\\resources\\chart\\';
            $data_file = 'data.json';

            if($step == 'scenario_1'){
                $net_cashflow = $app->request->post()["net_cashflow"];
                $scenario_1 = ['scenario_1' => $net_cashflow];
                $data_1 = Json::encode($scenario_1, JSON_NUMERIC_CHECK);
                if($this->writeFile($path.$data_file, $data_1))
                    return ['status'=>'Scenario 1 OK', 'file'=>$data_file];
                else
                    return ['status' => 'Failed'];
            }
            else if($step == 'scenario_2'){
                $net_cashflow_2 = $app->request->post()["net_cashflow"];

                if(!file_exists($path.$data_file)) {
                    $file = $this->readFile($path.$data_file);
                    $arr_file = Json::decode($file, true);
                    $arr_file['scenario_2'] = $net_cashflow_2;
                    $data_2 = Json::encode($arr_file, JSON_NUMERIC_CHECK);
                    $this->writeFile($path . $data_file, $data_2);
                    return ['status' => 'Scenario 2 OK', 'file' => $data_file];
                } else
                    return ['status' => 'Failed'];
            }
            else if($step == 'add'){
                $path = Yii::$app->view->theme->basePath.'/resources/chart/add/';
                $add_file = "add.json";

                $post = $app->request->post();

                $month_name = $post["field_name"];
                $sales = $post["sales_".$month_name];
                $cashflow = $post["cashflow_".$month_name];
                $payment = null;

                if(array_key_exists("payment_".$month_name, $post))
                {
                    $payment = $post["payment_".$month_name];
                    unset($post["payment_".$month_name]);
                }

                if(!file_exists($path.$add_file)) {
                    $scenario_1 = ['scenario_1' => [
                            "pay_1" => $post["pay_1"],
                            "pay_2" => $post["pay_2"],
                            "pay_3" => $post["pay_3"],
                            "pay_4" => $post["pay_4"],
                            "pay_5" => $post["pay_5"],
                            "pay_6" => $post["pay_6"],
                            "pay_7" => $post["pay_7"],
                            "pay_8" => $post["pay_8"],
                            "pay_9" => $post["pay_9"],
                            "pay_10" => $post["pay_10"],
                            "pay_11" => $post["pay_11"],
                            "pay_12" => $post["pay_12"],
                            "pay_13" => $post["pay_13"],
                            "pay_14" => $post["pay_14"],
                            "pay_15" => $post["pay_15"],
                            "total_project_cost" => $post["total_project_cost"],
                            "sales_price_w_vat" => $post["sales_price_w_vat"],
                            "net_sellable_area" => $post["net_sellable_area"],
                            $month_name => [
                                "sales" => $sales,
                                "cashflow" => $cashflow,
                                "payment" => $payment,
                            ]
                        ]
                    ];
                    $data_1 = Json::encode($scenario_1, JSON_NUMERIC_CHECK);

                    if($this->writeFile($path.$add_file, $data_1))
                        return ['status'=>'Scenario 1 OK', 'file'=>$add_file];
                    else
                        return ['status' => 'Failed'];
                }
                else{
                    $file = $this->readFile($path.$add_file);
                    $arr_file = Json::decode($file, true);
                    $arr_file['scenario_1'][$month_name] = [
                            "sales" => $sales,
                            "cashflow" => $cashflow,
                            "payment" => $payment,
                    ];
                    $data_1 = Json::encode($arr_file, JSON_NUMERIC_CHECK);

                    if($this->writeFile($path.$add_file, $data_1))
                        return ['status'=>'Scenario 1 OK', 'file'=>$add_file];
                    else
                        return ['status' => 'Failed'];
                }
            }
            else{
                return ['total_project_cost'=>filter_var($app->request->post('total_project_cost'), FILTER_SANITIZE_NUMBER_INT)];
            }
        }
    }

    private function getData(){
        $values_1 = [
            ['X'=>'T1', 'Y'=>'270'],
            ['X'=>'T2', 'Y'=>'342'],
            ['X'=>'T3', 'Y'=>'373'],
            ['X'=>'T4', 'Y'=>'389'],
            ['X'=>'T5', 'Y'=>'534'],
            ['X'=>'T6', 'Y'=>'547'],
            ['X'=>'T7', 'Y'=>'560'],
            ['X'=>'T8', 'Y'=>'580'],
            ['X'=>'T9', 'Y'=>'651'],
        ];
        $values_2 = [
            ['X'=>'T1', 'Y'=>'270'],
            ['X'=>'T2', 'Y'=>'342'],
            ['X'=>'T3', 'Y'=>'373'],
            ['X'=>'T4', 'Y'=>'318'],
            ['X'=>'T5', 'Y'=>'456'],
            ['X'=>'T6', 'Y'=>'462'],
            ['X'=>'T7', 'Y'=>'468'],
            ['X'=>'T8', 'Y'=>'480'],
            ['X'=>'T9', 'Y'=>'544'],
        ];
        $data = [
            'scenario_1' => [
                'linecolor' => '#5698D3',
                'title' => 'Scenario 1',
                'values' => $values_1,
            ],
            'scenario_2' => [
                'linecolor' => '#EE863F',
                'title' => 'Scenario 2',
                'values' => $values_2,
            ],
        ];
        return $data;
    }

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
