<?php

namespace vsoft\ad\controllers;

use Yii;
use vsoft\ad\models\AdProductReport;
use vsoft\ad\models\AdProductReportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
}
