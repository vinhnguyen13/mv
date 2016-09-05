<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel vsoft\coupon\models\CouponEventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('coupon', 'Coupon Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-event-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('coupon', 'Create Coupon Event'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('coupon', 'Codes'), ['/coupon/coupon-code'], ['class' => 'btn btn-info']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'description',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\news\models\Status::labels($model->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', \vsoft\news\models\Status::labels(),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    return \vsoft\coupon\models\CouponEvent::getTypes($model->type);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', \vsoft\coupon\models\CouponEvent::getTypes(),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'start_date',
                'value' => function ($model) {
                    return $model->start_date;
                },
                'format' => ['datetime', 'php: d/m/Y H:i a'],
                'filter' => false
            ],
            [
                'attribute' => 'end_date',
                'value' => function ($model) {
                    return $model->end_date;
                },
                'format' => ['datetime', 'php: d/m/Y H:i a'],
                'filter' => false
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
