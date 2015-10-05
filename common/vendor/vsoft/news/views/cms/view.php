<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model funson86\cms\models\CmsShow */

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
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
//            'catalog_id',
            [
                'label' => 'Catalog',
                'attribute' => 'catalog_id',
                'value' => $model->getCatalog()->one()->title,
            ],
            'title',
            'slug',
//            'surname',
//            'brief',
            'content:html',
            'seo_title',
            'seo_keywords',
            'seo_description',
            [
                'attribute' => 'banner',
                'format' => 'html',
                'value' => Html::img( Yii::$app->request->getHostInfo() . '/store/news/show/' . $model->banner,['width' => 200, 'alt' => $model->banner]),
            ],
//            'banner',
//            'template_show',
//            'author',
            'click',
            'status',
//            'created_at',
//            'updated_at:datetime',
        [
            'attribute' => 'updated_at',
            'value' => $model->updated_at,
            'format' => ['datetime','dd-MM-Y h:i:s A']
        ]
//            'create_by',
//            'update_by',
        ],
    ]) ?>

</div>
