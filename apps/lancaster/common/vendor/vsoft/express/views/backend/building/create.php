<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\express\models\LcBuilding */

$this->title = 'Create Building';
$this->params['breadcrumbs'][] = ['label' => 'Buildings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lc-building-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
