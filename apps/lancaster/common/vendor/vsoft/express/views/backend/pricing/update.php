<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcPricing */

$this->title = Yii::t('pricing', 'Update {modelClass}: ', [
    'modelClass' => 'Pricing',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('pricing', 'Pricings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('pricing', 'Update');
?>
<div class="lc-pricing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
