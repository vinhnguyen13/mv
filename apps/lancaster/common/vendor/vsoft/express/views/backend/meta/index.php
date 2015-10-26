<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\express\models\LcMetaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('meta', 'Meta');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-meta-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('meta', 'Create Meta'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'url:url',
            'metadata:ntext',
//            [
//                'attribute' => 'metadata',
//                'format' => 'raw',
//                'value' => function ($model) {return $model->metadata;},
////                'options' => ['width' => 'auto'],
//            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
