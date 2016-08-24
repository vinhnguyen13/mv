<?php

use yii\helpers\Html;
use funson86\cms\Module;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = Module::t('cms', 'Update ') . Module::t('cms', 'Building Project');
$this->params['breadcrumbs'][] = ['label' => Module::t('cms', 'Building Project'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cms-show-create">

    <?= $this->render('_form', [
        'model' => $model,
        'areaTypeMapLabels' => $areaTypeMapLabels,
        'areaTypes' => $areaTypes,
    	'investors' => $investors,
        'architects' => $architects,
        'contractors' => $contractors,
        'facility' => $facility
    ]) ?>

</div>
