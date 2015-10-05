<?php

use yii\helpers\Html;
use yii\grid\GridView;

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

//            'id',
            'title',
//            'catalog_id',
            [
                'label' => 'Catalog',
                'attribute' => 'catalog_id',
                'value' => function ($model) {
                    return $model->catalog->title;
                },
            ],
//            'slug',
//            'surname',
            // 'brief',
            // 'content:ntext',
            // 'seo_title',
            // 'seo_keywords',
            // 'seo_description',
            // 'banner',
            // 'template_show',
            // 'author',
            // 'click',
            // 'status',
            // 'created_at',
             'updated_at:datetime',
            // 'create_by',
            // 'update_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
