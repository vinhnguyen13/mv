<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponHistory */

$this->title = 'Create Coupon History';
$this->params['breadcrumbs'][] = ['label' => 'Coupon Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
