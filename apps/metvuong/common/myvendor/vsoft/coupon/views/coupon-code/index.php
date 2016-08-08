<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel vsoft\coupon\models\CouponCodeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('coupon', 'Coupon Codes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-code-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('coupon', 'Create Coupon'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('coupon', 'Create Random Coupon'), ['create-random'], ['class' => 'btn btn-info']) ?>
        <?= Html::a(Yii::t('coupon', 'Events'), ['/coupon/coupon-event'], ['class' => 'btn btn-info']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            [
                'label' => 'Coupon Event',
                'attribute' => 'cp_event_id',
                'value' => function ($model) {
                    if($model->couponEvent)
                        return $model->couponEvent->name;
                    return '';
                },
                'filter' => Html::activeDropDownList($searchModel, 'cp_event_id', ArrayHelper::map(\vsoft\coupon\models\CouponEvent::find()->where('status = :status', [':status' => 1])->asArray()->all(), 'id', 'name'),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\news\models\Status::labels($model->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', \vsoft\news\models\Status::labels(),['class'=>'form-control','prompt' => 'All']),
            ],
            'count',
            'limit',
            'amount',
            [
                'attribute' => 'amount_type',
                'value' => function ($model) {
                    return \vsoft\coupon\models\CouponCode::getAmountTypes($model->amount_type);
                },
                'filter' => Html::activeDropDownList($searchModel, 'amount_type', \vsoft\coupon\models\CouponCode::getAmountTypes(),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return $model->created_at;
                },
                'format' => ['datetime', 'php: d/m/Y H:i a'],
                'filter' => false
            ],

            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
