<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\news\models\CmsShow */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Cms Shows'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a(Yii::t('app', 'Back'), 'index', ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
//            'catalog_id',
            [
                'label' => 'Catalog',
                'attribute' => 'catalog_id',
                'value' => $model->getCatalog1()->one()->title,
            ],
            'title',
            'slug',
//            'surname',
            'brief',
            'content:html',
            'seo_title',
            'seo_keywords',
            'seo_description',
            [
                'attribute' => 'banner',
                'format' => 'html',
                'value' => Html::img(Yii::$app->request->getHostInfo() . '/store/news/show/' . $model->banner, ['width' => 200, 'alt' => $model->banner]),
            ],
//            'banner',
//            'template_show',
//            'author',
//            'click',
//            'status',
            [
                'attribute' => 'status',
                'value' => \vsoft\news\models\Status::labels($model->status)
            ],
            [
                'attribute' => 'created_by',
                'value' =>  $model->getUserName($model->created_by)
            ],
            [
                'attribute' => 'updated_by',
                'value' =>  $model->getUserName($model->updated_by)
            ],
            [
                'attribute' => 'updated_at',
                'value' => $model->updated_at,
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],

        ],
    ]) ?>
    <a id="back-to-top" href="#" class="btn btn-default btn-sm back-to-top pull-right"
       role="button" title="Back to Top" data-toggle="tooltip" data-placement="top">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>


</div>
