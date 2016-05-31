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
                'attribute' => 'created_at',
                'value' => function ($model) {
                    return $model->created_at;
                },
                'format' => ['datetime', 'php: d/m/Y H:i a'],
                'filter' => false
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
