<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdArchitect */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('architect', 'Ad Architects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-architect-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('architect', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('architect', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('architect', 'Are you sure you want to delete this item?'),
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
