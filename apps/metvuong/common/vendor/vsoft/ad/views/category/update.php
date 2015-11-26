<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = Yii::t('ad', 'Update ') . Yii::t('ad', 'Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('ad', 'Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-building-project-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
