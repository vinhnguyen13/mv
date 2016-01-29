<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdContractor */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('contractor', 'Ad Contractors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-contractor-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('contractor', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('contractor', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('contractor', 'Are you sure you want to delete this item?'),
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
            'phone',
            'fax',
            'website:url',
            'email:email',
            'description',
            [
                'attribute' => 'status',
                'value' => \vsoft\news\models\Status::labels($model->status)
            ],
        ],
    ]) ?>

</div>
