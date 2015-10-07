<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\news\models\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('banner', 'Banners');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('banner', 'Create Banner'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'name',
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => function ($model) { return Html::img(Yii::$app->request->getHostInfo() . '/store/news/banner/' . $model->image, ['width' => 200, 'alt' => $model->name]);}
            ],
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->name, ['view', 'id' => $model->id], ['style' => ['text-decoration' => 'none']]);
                    //return "<a href=\"view?id={$model->id}\" style=\"text-decoration: none; color: black;\">{$model->title}</a>";
                }
            ],
            'priority',
            'url:url',
            // 'description',
            // 'keyword',
            // 'alt_text',
            // 'additional_html',
            // 'created_at',
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return $model->updated_at;
                },
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],
            // 'created_by',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
