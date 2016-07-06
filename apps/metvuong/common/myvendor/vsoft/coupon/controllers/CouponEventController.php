<?php

namespace vsoft\coupon\controllers;

use Yii;
use vsoft\coupon\models\CouponEvent;
use vsoft\coupon\models\CouponEventSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CouponEventController implements the CRUD actions for CouponEvent model.
 */
class CouponEventController extends Controller
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (Yii::$app->user->can('/'.$this->module->id.'/*') ||
                                Yii::$app->user->can('/'.$this->id.'/*') ||
                                Yii::$app->user->can('/'.$this->id.'/'.$this->action->id));
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CouponEvent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponEventSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CouponEvent model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CouponEvent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CouponEvent();
        $post = Yii::$app->request->post();
        $start = isset($post["CouponEvent"]["start_date"]) ? $post["CouponEvent"]["start_date"] : null;
        $end = isset($post["CouponEvent"]["end_date"]) ? $post["CouponEvent"]["end_date"] : null;
        if(!empty($start) && !empty($end)) {
            $post["CouponEvent"]["start_date"] = strtotime($start);
            $post["CouponEvent"]["end_date"] = strtotime($end." 23:59");
        }

        if ($model->load($post) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CouponEvent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        $start = isset($post["CouponEvent"]["start_date"]) ? $post["CouponEvent"]["start_date"] : null;
        $end = isset($post["CouponEvent"]["end_date"]) ? $post["CouponEvent"]["end_date"] : null;
        if(!empty($start) && !empty($end)) {
            $post["CouponEvent"]["start_date"] = strtotime($start);
            $post["CouponEvent"]["end_date"] = strtotime($end." 23:59");
        }

        if ($model->load($post) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CouponEvent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CouponEvent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CouponEvent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CouponEvent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
