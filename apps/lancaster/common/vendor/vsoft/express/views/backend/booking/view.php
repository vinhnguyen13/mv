<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcBooking */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-booking-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
//            'lc_building_id',
            [
                'label' => 'Building',
                'attribute' => 'lc_building_id',
                'value' => $model->getLcBuilding()->building_name,
            ],
            'checkin',
            'checkout',
//            'apart_type',
            [
                'label' => 'Apartment Type',
                'attribute' => 'apart_type',
                'value' => $model->getApartType()->one()->name,
            ],
            'floorplan',
            'fullname',
            'phone',
            'email:email',
            'address',
            'passport_no',
            'nationality',
            'info:ntext',
//            'ip',
//            'agent',
//            'browser_type',
//            'browser_name',
//            'browser_version',
//            'platform',
            'created_at',
        ],
    ]) ?>

</div>
