<?php

namespace vsoft\coupon\controllers;

use Yii;
use vsoft\coupon\models\CouponHistory;
use vsoft\coupon\models\CouponHistorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CouponHistoryController implements the CRUD actions for CouponHistory model.
 */
class CouponHistoryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CouponHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CouponHistory model.
     * @param integer $user_id
     * @param integer $cp_code_id
     * @param integer $cp_event_id
     * @return mixed
     */
    public function actionView($user_id, $cp_code_id, $cp_event_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $cp_code_id, $cp_event_id),
        ]);
    }

    /**
     * Creates a new CouponHistory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CouponHistory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'cp_code_id' => $model->cp_code_id, 'cp_event_id' => $model->cp_event_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CouponHistory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param integer $cp_code_id
     * @param integer $cp_event_id
     * @return mixed
     */
    public function actionUpdate($user_id, $cp_code_id, $cp_event_id)
    {
        $model = $this->findModel($user_id, $cp_code_id, $cp_event_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'cp_code_id' => $model->cp_code_id, 'cp_event_id' => $model->cp_event_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CouponHistory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param integer $cp_code_id
     * @param integer $cp_event_id
     * @return mixed
     */
    public function actionDelete($user_id, $cp_code_id, $cp_event_id)
    {
        $this->findModel($user_id, $cp_code_id, $cp_event_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CouponHistory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param integer $cp_code_id
     * @param integer $cp_event_id
     * @return CouponHistory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $cp_code_id, $cp_event_id)
    {
        if (($model = CouponHistory::findOne(['user_id' => $user_id, 'cp_code_id' => $cp_code_id, 'cp_event_id' => $cp_event_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
