<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model funson86\cms\models\CmsShow */

$this->title = Yii::t('app', 'Add Investor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Investor'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
