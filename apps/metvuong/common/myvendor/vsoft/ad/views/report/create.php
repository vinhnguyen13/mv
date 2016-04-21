<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdProductReport */

$this->title = 'Create Product Report';
$this->params['breadcrumbs'][] = ['label' => 'Product Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-product-report-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
