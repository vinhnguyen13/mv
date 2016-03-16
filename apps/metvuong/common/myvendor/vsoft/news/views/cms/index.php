<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel vsoft\news\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Cms Shows');
$this->params['breadcrumbs'][] = $this->title;

$newsCatID = !empty(Yii::$app->params['newsCatID']) ? Yii::$app->params['newsCatID'] : 2;
?>
<div class="cms-show-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Cms Show'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php Pjax::begin();?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'banner',
                'format' => 'html',
                'contentOptions' => ['class' => 'text-center'],
                'value' => function ($model) {
                    $imgPath = Url::to( '/themes/metvuong1/resources/images/default-ads.jpg');
                    if($model->banner) {
                        $checkFile = file_exists(Yii::getAlias('@store')."/news/show/".$model->banner);
                        if($checkFile)
                            $imgPath = Url::to('/store/news/show/' . $model->banner);
                    } else {
                        $imgPath = Url::to( '/themes/metvuong1/resources/images/default-ads.jpg');// /frontend/web/themes/metvuong1/resources/images/default-ads.jpg
                    }
                    return Html::img($imgPath, ['width'=>100, 'height'=>100, 'alt'=>$model->banner, 'title'=>$model->brief]);
                }
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
                    if($model->getCatalog())
                        return $model->getCatalog()->one()->title;
                    return '';
                },
                'filter' => Html::activeDropDownList($searchModel, 'catalog_id', ArrayHelper::map(\vsoft\news\models\CmsCatalog::find()->where(['not in', 'id', [1, $newsCatID]])
                    ->andWhere('status = :status', [':status' => 1])->asArray()->all(), 'id', 'title'),['class'=>'form-control','prompt' => 'All']),
            ],

            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return \vsoft\news\models\Status::labels($model->status);
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', \vsoft\news\models\Status::labels(),['class'=>'form-control','prompt' => 'All']),
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
    <?php Pjax::end(); ?>
</div>
