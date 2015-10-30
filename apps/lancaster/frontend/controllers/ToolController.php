<?php
namespace frontend\controllers;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Json;
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
        if(Yii::$app->request->isPost){
//            $this->redirect(['chart']);
        }
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

            return ['total_project_cost'=>filter_var($app->request->post('total_project_cost'), FILTER_SANITIZE_NUMBER_INT)];
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
        return true;
    }

    private function readFile($filePath){
        $handle = fopen($filePath, 'r') or die('Cannot open file:  '.$filePath);
        $data = fread($handle,filesize($filePath));
        return $data;
    }
}
