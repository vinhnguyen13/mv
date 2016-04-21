<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdProductReport */
$ad_product = \vsoft\ad\models\AdProduct::findOne($model->product_id);
$this->title = 'Update Product Report: '.$ad_product->getAddress();
$this->params['breadcrumbs'][] = ['label' => 'Product Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'user_id' => $model->user_id, 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ad-product-report-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
