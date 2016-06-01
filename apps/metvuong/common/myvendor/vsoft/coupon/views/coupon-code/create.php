<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponCode */

$this->title = Yii::t('coupon', 'Create Coupon Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('coupon', 'Coupon Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-code-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
