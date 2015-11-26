<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CmsShow */

$this->title = Yii::t('ad', 'Sort ') . Yii::t('ad', 'Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('ad', 'Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>
	<?= $sort->render() ?>
</div>
