<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\craw\models\AdAgent */

$this->title = Yii::t('agent', 'Update {modelClass}: ', [
    'modelClass' => 'Ad Agent',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('agent', 'Ad Agents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('agent', 'Update');
?>
<div class="ad-agent-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
