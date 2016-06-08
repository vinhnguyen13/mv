<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel vsoft\ec\models\EcBalanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('ec', 'User Balance Management');
$this->params['breadcrumbs'][] = $this->title;
$data_user = ArrayHelper::map(\frontend\models\User::find()->innerJoin('ec_balance', 'user.id = ec_balance.user_id')->select(['user.id','username'])->all(), 'id', 'username');

?>
<div class="ec-balance-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'label' => Yii::t('ec', 'Username'),
                'attribute' => 'user_id',
                'value' => function ($model) {
                    if($model->user)
                        return $model->user->username;
                    return '';
                },
                'filter' => \kartik\select2\Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'user_id',
                    'data' => $data_user,
                    'options' => ['placeholder' => 'Select username ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])
            ],
            [
                'attribute' => 'amount',
                'format' => 'raw',
                'contentOptions'=>['style'=>'text-align:right'],
            ],
            [
                'format' => 'raw',
                'contentOptions'=>['style'=>'text-decoration: none; text-align:center'],
                'value' => function($model){
                    return Html::a(Yii::t('ec','Transaction history detail'), Yii::$app->urlManager->createUrl(['ec/ec-transaction-history/index', 'user_id' => $model->user_id]));
                }
            ],

//            [
//                'class' => 'yii\grid\ActionColumn',
//                'contentOptions'=>['style'=>'text-align:center']
//            ],
        ],
    ]); ?>
