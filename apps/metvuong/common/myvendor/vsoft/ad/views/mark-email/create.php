<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\MarkEmail */

$this->title = 'Create Mark Email';
$this->params['breadcrumbs'][] = ['label' => 'Mark Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-email-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
