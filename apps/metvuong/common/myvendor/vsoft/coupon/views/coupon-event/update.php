<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\coupon\models\CouponEvent */

$this->title = Yii::t('coupon', 'Update {modelClass}: ', [
    'modelClass' => 'Coupon Event',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('coupon', 'Coupon Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('coupon', 'Update');
?>
<div class="coupon-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
