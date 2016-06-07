<?php

namespace vsoft\ec\controllers;

use vsoft\ec\models\EcTransactionHistory;
use Yii;
use vsoft\ec\models\EcBalance;
use vsoft\ec\models\EcBalanceSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EcBalanceController implements the CRUD actions for EcBalance model.
 */
class EcBalanceController extends Controller
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
     * Lists all EcBalance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EcBalanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionTransaction($user_id)
    {
        $searchModel = new EcTransactionHistory();
        $dataProvider = $searchModel->getTransactions($user_id);

        $this->render('transaction', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }
}
