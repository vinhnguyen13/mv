<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\craw\models\AdAgentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('agent', 'Agents');
$this->params['breadcrumbs'][] = $this->title;
$source = [
    1 => Yii::t ( 'agent', 'Batdongsan.com.vn' ),
    2 => Yii::t ( 'agent', 'Homefinder.vn' )
];
$type = [
    1 => Yii::t ( 'agent', 'Công ty' ),
    2 => Yii::t ( 'agent', 'Cá nhân' )
];
?>
<div class="ad-agent-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('agent', 'Create Agent'), ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a(Yii::t('agent', 'Reset filter'), ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name',
            'address',
            'mobile',
            'phone',
            'fax',
            'email',
            'website:url',
            [
                'format' => 'raw',
                'attribute' => 'source',
                'value' => function ($model) {
                    return $model->source == 1 ? '<a href="//batdongsan.com.vn">Batdongsan.com.vn</a>' : '<a href="//homefinder.vn">Homefinder.vn</a>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'source', $source,['class'=>'form-control','prompt' => 'All']),
            ],
            'tax_code',
            'rating',
            'working_area',
            [
                'format' => 'html',
                'attribute' => 'type',
                'value' => function ($model) {
                    return $model->type == 1 ? Html::tag('span','Công ty') : Html::tag('span','Cá nhân');
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', $type,['class'=>'form-control','prompt' => 'All']),
            ],
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
