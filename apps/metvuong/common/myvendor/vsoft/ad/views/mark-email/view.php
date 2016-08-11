<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\MarkEmail */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Mark Emails', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-email-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->email], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->email], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email:email',
            'type',
            'count',
            'status',
            'send_time:datetime',
        ],
    ]) ?>

</div>
