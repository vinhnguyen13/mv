<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\ec\models\EcPackage */

$this->title = Yii::t('ec', 'Create Ec Package');
$this->params['breadcrumbs'][] = ['label' => Yii::t('ec', 'Ec Packages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ec-package-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
