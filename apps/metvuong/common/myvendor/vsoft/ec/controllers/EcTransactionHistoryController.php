<?php

namespace vsoft\ec\controllers;

use Yii;
use vsoft\ec\models\EcTransactionHistory;
use vsoft\ec\models\EcTransactionHistorySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EcTransactionHistoryController implements the CRUD actions for EcTransactionHistory model.
 */
class EcTransactionHistoryController extends Controller
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
     * Lists all EcTransactionHistory models.
     * @return mixed
     */
    public function actionIndex($user_id = null)
    {
        $searchModel = new EcTransactionHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $user_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_id' => $user_id
        ]);
    }


}
