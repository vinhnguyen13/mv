<?php

use yii\helpers\Html;
use funson86\cms\Module;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = Module::t('cms', 'Add ') . Module::t('cms', 'Building Project');
$this->params['breadcrumbs'][] = ['label' => Module::t('cms', 'Building Project'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
