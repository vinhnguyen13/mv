<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\news\models\Banner */

$this->title = Yii::t('banner', 'Create Banner');
$this->params['breadcrumbs'][] = ['label' => Yii::t('banner', 'Banners'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
