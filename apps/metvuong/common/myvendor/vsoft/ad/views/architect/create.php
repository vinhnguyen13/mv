<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model vsoft\ad\models\AdArchitect */

$this->title = Yii::t('architect', 'Create Ad Architect');
$this->params['breadcrumbs'][] = ['label' => Yii::t('architect', 'Ad Architects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-architect-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
