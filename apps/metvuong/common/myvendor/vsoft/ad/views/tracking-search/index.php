<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use funson86\cms\Module;
use vsoft\ad\models\AdBuildingProject;
use vsoft\ad\models\AdStreet;
use vsoft\ad\models\AdWard;
use vsoft\ad\models\AdCategoryGroup;
use vsoft\express\components\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('cms', 'Tracking Search');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->language = 'vi-VN';

$categoryGroup = AdCategoryGroup::find()->all();

$categoryDropDown = [];
foreach ($categoryGroup as $categoryG) {
	$categoryDropDown[implode(',', $categoryG->categories_id)] = $categoryG->nameTranslate;
}

?>
<div class="cms-show-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        	'location',
        	 [
				'attribute' => 'category',
				'value' => 'category.nameTranslate',
        		'filter' => Html::activeDropDownList($searchModel, 'category_search', $categoryDropDown, ['class' => 'form-control', 'prompt' => 'Loại BĐS'])
			],
        	'room_no',
        	'toilet_no',
        	[
        		'format' => 'raw',
        		'attribute' => 'price_min',
        		'value' => function($model) {
        			return $model->price_min ? StringHelper::formatCurrency(floatval($model->price_min)) : null;
    			}
        	],
        	[
        		'format' => 'raw',
        		'attribute' => 'price_max',
        		'value' => function($model) {
        			return $model->price_max ? StringHelper::formatCurrency(floatval($model->price_max)) : null;
    			}
        	],
        	'size_min',
        	'size_max',
        	'created_at:datetime'
        ],
    ]); ?>

</div>
