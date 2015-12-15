<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\news\models\Banner */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('banner', 'Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('banner', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('banner', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('banner', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'priority',
//            'image',
            [
                'attribute' => 'image',
                'format' => 'html',
                'value' => Html::img(Yii::$app->request->getHostInfo() . '/store/news/banner/' . $model->image, ['width' => 200, 'alt' => $model->name, 'title' => $model->image]),
            ],
            'url:url',
            'description',
            'keyword',
            'alt_text',
            'additional_html',
            [
                'attribute' => 'updated_at',
                'value' => $model->updated_at,
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],
//            'created_at',
//            'updated_at',
//            'created_by',
//            'updated_by',
        ],
    ]) ?>

</div>
