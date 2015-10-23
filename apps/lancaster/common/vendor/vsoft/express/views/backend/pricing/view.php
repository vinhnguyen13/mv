<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcPricing */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('pricing', 'Pricings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-pricing-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('pricing', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('pricing', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('pricing', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
//            'apart_type_id',
            'area',
            [
                'label' => 'Kind of apartment',
                'attribute' => 'apart_type_id',
                'value' => $model->getApartType()->one()->name,
            ],
            [
                'attribute' => 'monthly_rates',
                'format'=> [ 'decimal', 2],
                'value' => $model->monthly_rates,
            ],
            [
                'attribute' => 'daily_rates',
                'format'=>['decimal',2],
                'value' => $model->daily_rates,
            ],
            'description',
//            'created_at',
//            'updated_at',
//            'created_by',
//            'updated_by',
        ],
    ]) ?>

</div>
