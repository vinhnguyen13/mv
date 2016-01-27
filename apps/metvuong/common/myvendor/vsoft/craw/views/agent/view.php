<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\craw\models\AdAgent */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('agent', 'Ad Agents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-agent-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('agent', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('agent', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('agent', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'address',
            'mobile',
            'phone',
            'fax',
            'email',
            'website:url',
            'tax_code',
            'rating',
            'working_area',
            [
                'attribute' => 'source',
                'value' => $model->source == 1 ? "Batdongsan.com.vn" : "Homefinder.vn",
            ],
            [
                'attribute' => 'type',
                'value' => $model->type == 1 ? "Công ty" : "Cá nhân",
            ],
            [
                'attribute' => 'updated_at',
                'value' => $model->updated_at,
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],
        ],
    ]) ?>

</div>
