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
?>
<div class="ec-transaction-history-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <a href="<?=Yii::$app->urlManager->createUrl(['ec/ec-balance', 'EcBalanceSearch[user_id]' => $user_id])?>" class="btn btn-success">Back to balance</a>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
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
                'filter' => false
            ],
            [
                'attribute' => 'amount',
                'format' => 'raw',
                'contentOptions'=>['style'=>'text-align:right'],
            ],
            [
                'attribute' => 'action_type',
                'value' => function ($model) {
                    return \vsoft\ec\models\EcTransactionHistory::getActionType($model->action_type);
                },
                'filter' => Html::activeDropDownList($searchModel, 'action_type', \vsoft\ec\models\EcTransactionHistory::getActionType(),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'action_detail',
                'value' => function ($model) {
                    return \vsoft\ec\models\EcTransactionHistory::getActionDetail($model->action_detail);
                },
                'filter' => Html::activeDropDownList($searchModel, 'action_detail', \vsoft\ec\models\EcTransactionHistory::getActionDetail(),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'object_type',
                'value' => function ($model) {
                    return \vsoft\ec\models\EcTransactionHistory::getObjectType($model->object_type);
                },
                'filter' => Html::activeDropDownList($searchModel, 'object_type', \vsoft\ec\models\EcTransactionHistory::getObjectType(),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'label' => Yii::t('ec', 'Charge'),
                'attribute' => 'charge_id',
                'value' => function ($model) {
                    if($model->charge)
                        return $model->charge->charge." ".$model->charge->type;
                    else
                        return null;
                },
                'filter' => false
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\news\models\Status::labels($model->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', \vsoft\news\models\Status::labels(),['class'=>'form-control','prompt' => 'All']),
            ],
            'created_at:datetime'
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
