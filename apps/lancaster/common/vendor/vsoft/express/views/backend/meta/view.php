<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcMeta */

$this->title = $model->url;
$this->params['breadcrumbs'][] = ['label' => Yii::t('meta', 'Meta'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-meta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('meta', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('meta', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('meta', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'url:url',
            'metadata:ntext',
        ],
    ]) ?>

</div>
