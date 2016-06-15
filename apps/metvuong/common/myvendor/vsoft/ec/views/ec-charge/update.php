<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\ec\models\EcCharge */

$this->title = Yii::t('ec', 'Update {modelClass}: ', [
    'modelClass' => 'Ec Charge',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('ec', 'Ec Charges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('ec', 'Update');
?>
<div class="ec-charge-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
