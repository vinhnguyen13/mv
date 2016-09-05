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
    <div style="text-align: center; width: 100%; top: -20px;">
        <a style="font-size: 20px; display: inline-block; margin-top: 22px;" href="/admin/ad/mark-email">Reset filter</a>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'email:email',
            [
                'attribute' => 'type',
                'value' => function ($model) {
                    if($model->type == 1){
                        return 'Welcome agent';
                    }
                    if($model->type == 2){
                        return 'How use Dashboard';
                    }
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', [1=>'Welcome agent', 2=>'How use Dashboard'],['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\ad\models\MarkEmail::getSendStatus($model->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', \vsoft\ad\models\MarkEmail::getSendStatus(),['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'send_time',
                'value' => function ($model) {
                    return !empty($model->send_time) ? date('M d, Y, H:i:s', $model->send_time) : 0;
                },
                'filter' => false,
            ],
            [
                'attribute' => 'read_time',
                'value' => function ($model) {
                    return !empty($model->read_time) ? date('M d, Y, H:i:s', $model->read_time) : 0;
                },
                'filter' => Html::activeDropDownList($searchModel, 'filter_read_time', [1=>'Yes'],['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'attribute' => 'click_time',
                'value' => function ($model) {
                    return !empty($model->click_time) ? date('M d, Y, H:i:s', $model->click_time) : 0;
                },
                'filter' => Html::activeDropDownList($searchModel, 'filter_click_time', [1=>'Yes'],['class'=>'form-control','prompt' => 'All']),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'Actions',
                'template' => '{view} {delete}',
            ],
        ],
    ]); ?>
</div>
