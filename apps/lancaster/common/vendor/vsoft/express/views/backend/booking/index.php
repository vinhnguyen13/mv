<?php

use yii\grid\GridView;
use yii\helpers\Html;
use vsoft\express\models\LcBuilding;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\express\models\LcBookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bookings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-booking-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Booking', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            [
//                'attribute' => 'lc_building',
//                'value' => 'lc_building.building_name'
//            ],
            [
                'label' => 'Building',
                'attribute' => 'lc_building_id',
                'value' => function ($data) {
                    if ($data->getLcBuilding()) {
                        $name = $data->getLcBuilding()->building_name;
                        return $name;
                    }
                    return '';
                },
            ],
//            'id',
//            'buildingName',
//            'checkin',
//            'checkout',
            [
                'attribute' => 'checkin',
                'format' =>  ['date', 'php:d-m-Y H:i'],
//                'options' => ['width' => '200']
            ],
            [
                'attribute' => 'checkout',
                'format' =>  ['date', 'php:d-m-Y H:i'],
//                'options' => ['width' => '200']
            ],
//            'apart_type',
            'fullname',
            'floorplan',
            'phone',
            'email:email',
            'address',
            'passport_no',
            // 'nationality',
            // 'info:ntext',
            // 'ip',
            // 'agent',
            // 'browser_type',
            // 'browser_name',
            // 'browser_version',
            // 'platform',
            [
                'label' => 'Booking Date',
                'attribute' => 'created_at',
                'format' =>  ['date', 'php:d-m-Y H:i'],
//                'options' => ['width' => '200']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
