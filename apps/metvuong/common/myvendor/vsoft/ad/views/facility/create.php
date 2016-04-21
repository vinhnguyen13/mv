<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdFacility */

$this->title = Yii::t('facility', 'Create Ad Facility');
$this->params['breadcrumbs'][] = ['label' => Yii::t('facility', 'Ad Facilities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-facility-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
