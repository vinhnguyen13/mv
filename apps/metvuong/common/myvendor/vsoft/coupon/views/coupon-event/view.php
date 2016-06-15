<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponEvent */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('coupon', 'Coupon Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('coupon', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('coupon', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('coupon', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description',
            [
                'attribute' => 'status',
                'value' => \vsoft\news\models\Status::labels($model->status)
            ],
            [
                'attribute' => 'created_at',
                'value' => $model->created_at,
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],
        ],
    ]) ?>

</div>
