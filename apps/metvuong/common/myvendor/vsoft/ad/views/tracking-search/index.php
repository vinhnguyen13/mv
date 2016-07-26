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
use frontend\models\MapSearch;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCss('.summary {position: absolute; right: 0px; top: -20px;} .cms-show-index {padding-top: 40px; position: relative;} .filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 28px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');

$this->title = Yii::t('cms', 'Tracking Search');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->language = 'vi-VN';

$categoryGroup = AdCategoryGroup::find()->all();

$categoryDropDown = [];
foreach ($categoryGroup as $categoryG) {
	$categoryDropDown[implode(',', $categoryG->categories_id)] = $categoryG->nameTranslate;
}

$mapSort = MapSearch::mapSort();
?>
<div class="cms-show-index">
	<div style="text-align: center; width: 100%; position: absolute; top: -20px;"><a style="font-size: 20px; display: inline-block; margin-top: 22px;" href="<?= Url::to(['/ad/tracking-search']) ?>">Reset Filter</a></div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        	[
        		'attribute' => 'finder',
				'value' => function($model) {
        	 		if($model->user_id) {
        	 			return $model->user->username;
        	 		} else {
        	 			return $model->ip;
        	 		}
        		},
        		'filter' => Html::activeTextInput($searchModel, 'finder_search', ['class' => 'form-control min price'])
			],
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
        	[
        		'attribute' => 'order_by',
        		'value' => function($model) use ($mapSort) {
        			return $model->order_by ? $mapSort[$model->order_by] : null;
    			},
    			'filter' => Html::activeDropDownList($searchModel, 'order_by', $mapSort, ['class' => 'form-control', 'prompt' => 'Chọn loại sắp xếp'])
        	],
        	'created_at:datetime'
        ],
    ]); ?>

</div>
