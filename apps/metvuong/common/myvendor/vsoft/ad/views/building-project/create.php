<?php

use yii\helpers\Html;
use funson86\cms\Module;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = Module::t('ad', 'Add ') . Module::t('ad', 'Building Project');
$this->params['breadcrumbs'][] = ['label' => Module::t('ad', 'Building Project'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ad-building-project-create">

    <?= $this->render('_form', [
        'model' => $model,
    	'areaTypeMapLabels' => $areaTypeMapLabels,
    	'areaTypes' => $areaTypes,
//    	'categories' => $categories,
        'investors' => $investors,
        'architects' => $architects,
        'contractors' => $contractors,
        'facility' => $facility
    ]) ?>

</div>
