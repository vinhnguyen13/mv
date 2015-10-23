<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcApartmentType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('apartment_type', 'Apartment Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-apartment-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('apartment_type', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('apartment_type', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('apartment_type', 'Are you sure you want to delete this item?'),
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
            'created_at:datetime',
            'updated_at:datetime',
//            'created_by',
//            'updated_by',
        ],
    ]) ?>

</div>
