<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdProductReport */

$ad_product = \vsoft\ad\models\AdProduct::findOne($model->product_id);
$this->title = $ad_product->getAddress();
$this->params['breadcrumbs'][] = ['label' => 'Product Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-product-report-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'user_id' => $model->user_id, 'product_id' => $model->product_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'user_id' => $model->user_id, 'product_id' => $model->product_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'user_id',
                'value' => \frontend\models\User::findOne($model->user_id)->username
            ],
            [
                'attribute' => 'product_id',
                'value' => \vsoft\ad\models\AdProduct::findOne($model->product_id)->getAddress()
            ],
            'type',
            'description',
            'ip',
            [
                'attribute' => 'status',
                'value' => \vsoft\news\models\Status::labels($model->status)
            ],
            [
                'attribute' => 'report_at',
                'value' => $model->report_at,
                'format' => ['datetime', 'php: d/m/Y H:i a']
            ],
        ],
    ]) ?>

</div>
