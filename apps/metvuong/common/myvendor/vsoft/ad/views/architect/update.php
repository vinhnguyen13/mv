<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdArchitect */

$this->title = Yii::t('architect', 'Update {modelClass}: ', [
    'modelClass' => 'Ad Architect',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('architect', 'Ad Architects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('architect', 'Update');
?>
<div class="ad-architect-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
