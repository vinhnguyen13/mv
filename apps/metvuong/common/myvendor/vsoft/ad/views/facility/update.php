<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdFacility */

$this->title = Yii::t('facility', 'Update {modelClass}: ', [
    'modelClass' => 'Ad Facility',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('facility', 'Ad Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('facility', 'Update');
?>
<div class="ad-facility-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
