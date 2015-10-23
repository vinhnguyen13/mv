<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcPricing */

$this->title = Yii::t('pricing', 'Create Pricing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('pricing', 'Pricings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-pricing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
