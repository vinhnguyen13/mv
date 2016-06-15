<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\ec\models\EcCharge */

$this->title = Yii::t('ec', 'Create Ec Charge');
$this->params['breadcrumbs'][] = ['label' => Yii::t('ec', 'Ec Charges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ec-charge-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
