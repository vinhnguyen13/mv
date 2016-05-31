<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponEvent */

$this->title = Yii::t('coupon', 'Create Coupon Event');
$this->params['breadcrumbs'][] = ['label' => Yii::t('coupon', 'Coupon Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coupon-event-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
