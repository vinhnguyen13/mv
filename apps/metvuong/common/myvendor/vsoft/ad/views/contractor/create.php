<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdContractor */

$this->title = Yii::t('contractor', 'Create Ad Contractor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('contractor', 'Ad Contractors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-contractor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
