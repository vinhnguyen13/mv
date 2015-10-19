<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\news\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Cms Shows');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Cms Show'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'banner',
                'format' => 'html',
                'value' => function ($model) { return Html::img(Yii::$app->request->getHostInfo() . '/store/news/show/' . $model->banner, ['width' => 100, 'alt' => $model->banner, 'title' => $model->brief]); }
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->title, ['view', 'id' => $model->id], ['style' => ['text-decoration' => 'none'],'data-toggle' => 'tooltip', 'data-placement' => 'right', 'title' => strlen($model->brief) > 300 ? mb_substr($model->brief, 0, 300) . '...' : $model->brief]);
                    //return "<a href=\"view?id={$model->id}\" style=\"text-decoration: none; color: black;\">{$model->title}</a>";
                }
            ],
            [
                'label' => 'Catalog',
                'attribute' => 'catalog_id',
                'value' => function ($model) {
                    return $model->getCatalog()->one()->title;
                },
            ],

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\news\models\Status::labels($model->status);
                },
                'options' => ['width' => '100']
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    return $model->updated_at;
                },
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
