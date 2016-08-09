<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\ec\models\EcTransactionHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $user_id */

$this->title = Yii::t('ec', 'Transaction history detail');
$this->params['breadcrumbs'][] = $this->title;
$EcTransactionHistory = new \vsoft\ec\models\EcTransactionHistory();
?>
<div class="ec-transaction-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <a href="<?=Yii::$app->urlManager->createUrl(['ec/ec-balance', 'EcBalanceSearch[user_id]' => $user_id])?>" class="btn btn-success">Back to balance</a>
    </p>
<?php Pjax::begin(); ?>
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
                'filter' => true
            ],
            [
                'attribute' => 'amount',
                'format' => 'raw',
                'contentOptions'=>['style'=>'text-align:right'],
            ],[
                'attribute' => 'balance',
                'format' => 'raw',
                'contentOptions'=>['style'=>'text-align:right'],
            ],
            [
                'attribute' => 'object_type',
                'value' => function ($model) {
                    return $model->getObjectType();
                },
                'filter' => Html::activeDropDownList($searchModel, 'object_type', $EcTransactionHistory->getObjectType(),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getTransactionStatus();
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', $EcTransactionHistory->getTransactionStatus(),['class'=>'form-control','prompt' => 'All']),
            ],
            'created_at:datetime'
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
