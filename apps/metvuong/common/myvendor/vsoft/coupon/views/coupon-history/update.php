<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponHistory */

$this->title = 'Update Coupon History: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Coupon Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'user_id' => $model->user_id, 'cp_code_id' => $model->cp_code_id, 'cp_event_id' => $model->cp_event_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="coupon-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
