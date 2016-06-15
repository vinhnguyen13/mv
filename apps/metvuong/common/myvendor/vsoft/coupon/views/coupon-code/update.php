<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponCode */

$this->title = Yii::t('coupon', 'Update {modelClass}: ', [
    'modelClass' => 'Coupon Code',
]) . $model->code;
$this->params['breadcrumbs'][] = ['label' => Yii::t('coupon', 'Coupon Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('coupon', 'Update');
?>
<div class="coupon-code-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
