<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\ec\models\EcPaymentMethod */

$this->title = Yii::t('ec', 'Update {modelClass}: ', [
    'modelClass' => 'Ec Payment Method',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('ec', 'Ec Payment Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('ec', 'Update');
?>
<div class="ec-payment-method-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
