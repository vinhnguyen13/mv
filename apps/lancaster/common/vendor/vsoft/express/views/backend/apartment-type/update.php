<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcApartmentType */

$this->title = Yii::t('apartment_type', 'Update {modelClass}: ', [
    'modelClass' => 'Apartment Type',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('apartment_type', 'Apartment Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('apartment_type', 'Update');
?>
<div class="lc-apartment-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
