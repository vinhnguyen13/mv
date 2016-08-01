<?php

namespace vsoft\ad\controllers;

use frontend\models\User;
use Yii;
use vsoft\ad\models\AdProductReport;
use vsoft\ad\models\AdProductReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ReportController implements the CRUD actions for AdProductReport model.
 */
class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdProductReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdProductReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdProductReport model.
     * @param integer $user_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionView($user_id, $product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $product_id),
        ]);
    }

    /**
     * Creates a new AdProductReport model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdProductReport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'product_id' => $model->product_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdProductReport model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionUpdate($user_id, $product_id)
    {
        $model = $this->findModel($user_id, $product_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'product_id' => $model->product_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AdProductReport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionDelete($user_id, $product_id)
    {
        $this->findModel($user_id, $product_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdProductReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param integer $product_id
     * @return AdProductReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $product_id)
    {
        if (($model = AdProductReport::findOne(['user_id' => $user_id, 'product_id' => $product_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetUserReport($product_id, $last_time = null){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            if (!empty($product_id)) {
                $sql = "select r.user_id, u.username, u.email, p.avatar, r.report_at from ad_product_report r inner join user u on r.user_id = u.id
                          inner join profile p on r.user_id = p.user_id where product_id = {$product_id} ";
                if($last_time)
                    $sql = $sql. "and r.report_at < {$last_time} ";
                $sql = $sql. "order by r.report_at asc limit 10";
                $list_user = User::getDb()->createCommand($sql)->queryAll();
                if (count($list_user) > 0) {
                    return $this->renderAjax('listUser', ['list_user' => $list_user, 'product_id' => $product_id]);
                }
            }
        }
        throw new NotFoundHttpException('The requested page does not exists.');
    }

    public function actionGetUserReportLoadMore($product_id, $last_time = null){
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_HTML;
            if (!empty($product_id)) {
                $sql = "select r.user_id, u.username, u.email, p.avatar, r.report_at from ad_product_report r inner join user u on r.user_id = u.id
                          inner join profile p on r.user_id = p.user_id where product_id = {$product_id} ";
                if($last_time)
                    $sql = $sql. "and r.report_at < {$last_time} ";
                $sql = $sql. "order by r.report_at asc limit 10";
                $list_user = User::getDb()->createCommand($sql)->queryAll();
                if (count($list_user) > 0) {
                    return $this->renderAjax('listUser_item',['list_user' => $list_user]);
                }
            }
        }
        throw new NotFoundHttpException('The requested list user item does not exists.');
    }
}
