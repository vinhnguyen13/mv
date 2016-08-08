<?php

namespace vsoft\coupon\controllers;

use frontend\models\Payment;
use vsoft\coupon\models\Coupon;
use vsoft\coupon\models\CouponHistory;
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
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            return (Yii::$app->user->can('/'.$this->module->id.'/*') ||
                                Yii::$app->user->can('/'.$this->id.'/*') ||
                                Yii::$app->user->can('/'.$this->id.'/'.$this->action->id));
                        },
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
                $code = CouponCode::generateCodeExists($coupon_code, $length, $prefix, $suffix, $numbers, $letters, $symbols, $random_register, $mask);
                if(!empty($code)){
                    $event_id = (isset($post["CouponCode"]["cp_event_id"])) ? intval($post["CouponCode"]["cp_event_id"]) : 0;
                    $limit = (isset($post["CouponCode"]["limit"])) ? intval($post["CouponCode"]["limit"]) : 2;
                    $amount = (isset($post["CouponCode"]["amount"])) ? intval($post["CouponCode"]["amount"]) : 0;
                    $amount_type = (isset($post["CouponCode"]["amount_type"])) ? intval($post["CouponCode"]["amount_type"]) : 1;
                    $model = new CouponCode();
                    $model->code = $code;
                    $model->cp_event_id = $event_id;
                    $model->limit = $limit;
                    $model->amount = $amount;
                    $model->amount_type = $amount_type;
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

    public function actionPromotion()
    {
        $model = new CouponCode();
        if (Yii::$app->request->isPost) {
            $user_id = \Yii::$app->request->post('user_id');
            $code_id = \Yii::$app->request->post('code_id');
            $cpCode = CouponCode::findOne($code_id);
            if(!empty($cpCode)){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $res = CouponHistory::checkCoupon($user_id, $cpCode->code);
                if (!empty($res['error_code'] == 0) && !empty($res['result']->couponCode->amount)) {
                    Payment::me()->processTransactionByCoupon($user_id, $res['result']);
                    return ['error_code'=>0, 'result'=>Yii::t('coupon', 'Thank you for using coupon')];
                }else if($res['error_message']){
                    return ['error_code'=>2, 'error_message'=>$res['error_message']];
                }
            }
            return ['error_code'=>2, 'error_message'=>'Not found !'];
        } else {
            return $this->render('promotion', [
                'model' => $model,
            ]);
        }

    }
}
