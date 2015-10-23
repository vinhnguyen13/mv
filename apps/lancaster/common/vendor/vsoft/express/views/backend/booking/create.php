<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcBooking */

$this->title = 'Create Booking';
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-booking-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
