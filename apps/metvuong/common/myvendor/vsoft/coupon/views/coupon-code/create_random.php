<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponCode */

$this->title = Yii::t('coupon', 'Create Coupon Random Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('coupon', 'Coupon Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-code-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('create_random_form', [
        'model' => $model,
    ]) ?>

</div>
