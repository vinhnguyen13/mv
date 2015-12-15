<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\news\models\Banner */

$this->title = Yii::t('banner', 'Update {modelClass}: ', [
    'modelClass' => 'Banner',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('banner', 'Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('banner', 'Update');
?>
<div class="banner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
