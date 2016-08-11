<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\ad\models\MarkEmailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mark Emails';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-email-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'email:email',
            'type',
            'count',
            'status',
            'send_time:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'template' => '{view} {delete}',
            ],
        ],
    ]); ?>
</div>
