<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\craw\models\AdAgentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('agent', 'Agents');
$this->params['breadcrumbs'][] = $this->title;
$type = [
    1 => Yii::t ( 'agent', 'Batdongsan.com.vn' ),
    2 => Yii::t ( 'agent', 'Homefinder.vn' )
];
?>
<div class="ad-agent-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('agent', 'Create Ad Agent'), ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'format' => 'raw',
                'attribute' => 'source',
                'value' => function ($model) {
                    return $model->source == 1 ? '<a href="//batdongsan.com.vn">Batdongsan.com.vn</a>' : '<a href="//homefinder.vn">Homefinder.vn</a>';
                },
                'filter' => Html::activeDropDownList($searchModel, 'source', $type,['class'=>'form-control','prompt' => 'All']),
            ],
            // 'fax',
            // 'email:email',
            // 'website',
            // 'tax_code',
            // 'rating',
            // 'working_area',
            // 'source',
            // 'type',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
