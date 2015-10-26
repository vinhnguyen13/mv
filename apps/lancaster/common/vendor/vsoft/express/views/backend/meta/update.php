<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcMeta */

$this->title = Yii::t('meta', 'Update {modelClass}: ', [
    'modelClass' => 'Meta',
]) . ' ' . $model->url;
$this->params['breadcrumbs'][] = ['label' => Yii::t('meta', 'Meta'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('meta', 'Update');
?>
<div class="lc-meta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
