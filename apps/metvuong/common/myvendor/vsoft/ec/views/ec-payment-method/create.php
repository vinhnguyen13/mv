<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\ec\models\EcPaymentMethod */

$this->title = Yii::t('ec', 'Create Ec Payment Method');
$this->params['breadcrumbs'][] = ['label' => Yii::t('ec', 'Ec Payment Methods'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ec-payment-method-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
