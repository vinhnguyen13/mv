<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcApartmentType */

$this->title = Yii::t('apartment_type', 'Create Apartment Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('apartment_type', 'Apartment Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-apartment-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
