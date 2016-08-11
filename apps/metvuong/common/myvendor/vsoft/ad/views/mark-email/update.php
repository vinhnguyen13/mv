<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\MarkEmail */

$this->title = 'Update Mark Email: ' . $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Mark Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->email]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mark-email-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
