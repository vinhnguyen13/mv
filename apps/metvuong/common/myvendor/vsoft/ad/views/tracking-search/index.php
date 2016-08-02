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
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\TrackingSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCss('.summary {position: absolute; right: 0px; top: -20px;} .cms-show-index {padding-top: 40px; position: relative;} .filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 28px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');
$this->registerJsFile(Yii::getAlias('@web') . '/js/tracking-search.js', ['depends' => ['yii\web\YiiAsset']]);

$this->title = Yii::t('cms', 'Tracking Search');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->language = 'vi-VN';

$categoryGroup = AdCategoryGroup::find()->all();

$categoryDropDown = [];
foreach ($categoryGroup as $categoryG) {
	$categoryDropDown[implode(',', $categoryG->categories_id)] = $categoryG->nameTranslate;
}

$mapSort = MapSearch::mapSort();
$type = AdProduct::getAdTypes();

$sourceDropDown = [
    TrackingSearch::FROM_QUICK_SEARCH => 'Tìm nhanh',
    TrackingSearch::FROM_IN_PAGE => 'Tìm tại trang',
    TrackingSearch::FROM_HOME => 'Từ trang Home',
    TrackingSearch::FROM_OTHER_PAGE => 'Từ trang khác',
    TrackingSearch::FROM_OTHER_SITE => 'Từ Website khác',
    TrackingSearch::FROM_DIRECT => 'Vào trực tiếp',
];
?>
<div style="position: relative; z-index: 1;">
	<a style="font-size: 20px; display: inline-block;" href="<?= Url::to(['/ad/tracking-search']) ?>">Show all</a>
	<span style="margin: 12px;"></span>
	<a style="font-size: 20px; display: inline-block;" href="<?= Url::to(['/ad/tracking-search/group']) ?>">Group theo đối tượng</a>
</div>
<div class="cms-show-index">
	<div style="text-align: center; width: 100%; position: absolute; top: -20px;">
		<a style="font-size: 20px; display: inline-block; margin-top: 22px;" href="<?= Url::to(['/ad/tracking-search']) ?>">Reset filter</a>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
        	[
        		'attribute' => 'alias',
        		'filter' => Html::activeDropDownList($searchModel, 'finder_filter', [0 => 'Có đăng nhập', 1 => 'Không đăng nhập', 2 => 'Lọc theo...'], ['class' => 'form-control min price', 'prompt' => 'Tất cả', 'style' => 'display: block;']) .
        					Html::activeTextInput($searchModel, 'alias', ['class' => 'form-control min price', 'style' => $searchModel->finder_filter != 2 ? 'display: none;' : ''])
			],
        	[
        		'attribute' => 'type',
        		'value' => function($model) use ($type) {
        			return $type[$model->type];
    			},
    			'filter' => Html::activeDropDownList($searchModel, 'type', $type, ['class' => 'form-control', 'prompt' => 'Hình thức'])
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
        	[
        		'attribute' => 'is_mobile',
        		'value' => function($model) use ($mapSort) {
        			return $model->is_mobile ? 'yes' : 'no';
    			},
    			'filter' => Html::activeDropDownList($searchModel, 'is_mobile', ['0' => 'no', '1' => 'yes'], ['class' => 'form-control', 'prompt' => 'all'])
        	],
        	[
        		'format' => 'raw',
        		'attribute' => 'from',
        		'value' => function($model) use ($sourceDropDown, $searchModel) {
        			if($model->from == TrackingSearch::FROM_HOME) {
        				$return = $sourceDropDown[TrackingSearch::FROM_HOME];
        			} else if($model->from == TrackingSearch::FROM_DIRECT) {
        				$return = $sourceDropDown[TrackingSearch::FROM_DIRECT];
        			} else if($model->from == TrackingSearch::FROM_IN_PAGE) {
        				$return = $sourceDropDown[TrackingSearch::FROM_IN_PAGE];
        			} else if($model->from == TrackingSearch::FROM_OTHER_PAGE) {
        				if($searchModel->from_filter == TrackingSearch::FROM_OTHER_PAGE) {
        					$split = explode('/', $model->referer);
        					$return = '<span title="' . $model->referer . '">' . implode('/', array_slice($split, 3)) . '</span>';
        				} else {
        					$return = $sourceDropDown[TrackingSearch::FROM_OTHER_PAGE];
        				}
        			} else if($model->from == TrackingSearch::FROM_OTHER_SITE) {
        				if($searchModel->from_filter == TrackingSearch::FROM_OTHER_SITE) {
        					$return = '<span title="' . $model->referer . '">' . $model->referer . '</span>';
        				} else {
        					$return = $sourceDropDown[TrackingSearch::FROM_OTHER_SITE];
        				}
        			} else if($model->from == TrackingSearch::FROM_QUICK_SEARCH) {
        				$return = $sourceDropDown[TrackingSearch::FROM_QUICK_SEARCH];
        			}
        			
        			return $return;
    			},
    			'filter' => Html::activeDropDownList($searchModel, 'from_filter', $sourceDropDown, ['class' => 'form-control', 'prompt' => 'all']),
    			'contentOptions' => ['style' => 'max-width: 280px; overflow: hidden; text-overflow: ellipsis;']
        	],
        	'created_at:datetime'
        ],
    ]); ?>

</div>
