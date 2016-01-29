<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdContractor */

$this->title = Yii::t('contractor', 'Update {modelClass}: ', [
    'modelClass' => 'Ad Contractor',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('contractor', 'Ad Contractors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('contractor', 'Update');
?>
<div class="ad-contractor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
