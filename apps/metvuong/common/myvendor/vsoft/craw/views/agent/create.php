<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\craw\models\AdAgent */

$this->title = Yii::t('agent', 'Create Ad Agent');
$this->params['breadcrumbs'][] = ['label' => Yii::t('agent', 'Ad Agents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-agent-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
