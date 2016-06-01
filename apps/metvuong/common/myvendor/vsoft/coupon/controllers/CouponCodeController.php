<?php

namespace vsoft\coupon\controllers;

use vsoft\coupon\models\Coupon;
use Yii;
use vsoft\coupon\models\CouponCode;
use vsoft\coupon\models\CouponCodeSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * CouponCodeController implements the CRUD actions for CouponCode model.
 */
class CouponCodeController extends Controller
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
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }

    /**
     * Lists all CouponCode models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CouponCode model.
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
     * Creates a new CouponCode model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CouponCode();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }

    }

    public function actionCreateRandom()
    {
        $model = new CouponCode();
        $post = Yii::$app->request->post();
        if(isset($post['length'])){
            $no_of_coupons = intval($post['no_of_coupons']);
            $length = $post['length'];
            $prefix = $post['prefix'];
            $suffix = $post['suffix'];
            $numbers = $post['numbers'];
            $letters = $post['letters'];
            $symbols = $post['symbols'];
            $random_register = $post['random_register'] == 'false' ? false : true;
            $mask = $post['mask'] == '' ? false : $post['mask'];
            for ($i = 0; $i < $no_of_coupons; $i++) {
                $coupon_code = Coupon::generate($length, $prefix, $suffix, $numbers, $letters, $symbols, $random_register, $mask);
                $code = CouponCode::checkCodeExists($coupon_code, $length, $prefix, $suffix, $numbers, $letters, $symbols, $random_register, $mask);
                if(!empty($code)){
                    $event_id = (isset($post["CouponCode"]["cp_event_id"])) ? intval($post["CouponCode"]["cp_event_id"]) : 0;
                    $type = (isset($post["CouponCode"]["type"])) ? intval($post["CouponCode"]["type"]) : 2;
                    $model = new CouponCode();
                    $model->code = $code;
                    $model->cp_event_id = $event_id;
                    $model->type = $type;
                    $model->save(false);
                }
            }
            return $this->redirect('index');
        }

        return $this->render('create_random', [
            'model' => $model,
        ]);

    }

    /**
     * Updates an existing CouponCode model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing CouponCode model.
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
     * Finds the CouponCode model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CouponCode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CouponCode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


}
