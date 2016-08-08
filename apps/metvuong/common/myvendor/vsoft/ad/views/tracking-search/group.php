<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdCategoryGroup;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\TrackingSearch;
use frontend\models\MapSearch;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCss('table td{padding: 2px 10px;} .separator {margin: 0 8px;} .summary {position: absolute; right: 0px; top: -20px;} .cms-show-index {padding-top: 40px; position: relative;} .filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 18px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');
$this->registerJsFile(Yii::getAlias('@web') . '/js/tracking-search.js', ['depends' => ['yii\web\YiiAsset']]);

$this->title = Yii::t('cms', 'Tracking Search');
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->language = 'vi-VN';

$type = AdProduct::getAdTypes();

$categoryGroup = AdCategoryGroup::find()->all();
$categoryDropDown = [];
foreach ($categoryGroup as $categoryG) {
	$categoryDropDown[implode(',', $categoryG->categories_id)] = $categoryG->nameTranslate;
}

$sourceDropDown = [
	TrackingSearch::FROM_QUICK_SEARCH => 'Tìm nhanh',
	TrackingSearch::FROM_IN_PAGE => 'Tìm tại trang',
	TrackingSearch::FROM_HOME => 'Từ trang Home',
	TrackingSearch::FROM_OTHER_PAGE => 'Từ trang khác',
	TrackingSearch::FROM_OTHER_SITE => 'Từ Website khác',
	TrackingSearch::FROM_DIRECT => 'Vào trực tiếp',
];

$mapSort = MapSearch::mapSort();
?>
<div class="cms-show-index">
	<div style="text-align: center; width: 100%; position: absolute; top: -20px;">
		<div style="float: left; margin-top: 18px;">
			<a style="font-size: 20px; display: inline-block;" href="<?= Url::to(['/ad/tracking-search']) ?>">Show all</a>
			<span style="margin: 12px;"></span>
			<a style="font-size: 20px; display: inline-block;" href="<?= Url::to(['/ad/tracking-search/group']) ?>">Group theo đối tượng</a>
		</div>
	</div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
    	'filterModel' => $searchModel,
        'columns' => [
        	[
        		'label' => 'Người tìm',
        		'attribute' => 'alias',
        		'filter' => Html::activeDropDownList($searchModel, 'finder_filter', [0 => 'Có đăng nhập', 1 => 'Không đăng nhập', 2 => 'Lọc theo...'], ['class' => 'form-control min price', 'prompt' => 'Tất cả', 'style' => 'display: block;']) .
        					Html::activeTextInput($searchModel, 'alias', ['class' => 'form-control min price', 'style' => $searchModel->finder_filter != 2 ? 'display: none;' : ''])
			],
        	[
        		'format' => 'raw',
        		'value' => function($model) use($type, $categoryDropDown, $sourceDropDown, $mapSort)  {
        			$query = new Query();
        			$query->from('tracking_search');
        			$query->where(['alias' => $model['alias']]);
        			$query->orderBy('created_at');
        			$query->limit(50);
        			$trackings = $query->all();
        			
        			$table = '<table>';
        			
        			foreach ($trackings as $tracking) {
        				$tr = '<tr>';
        				
        				$tr .= '<td>' . date("h:i:s d-m", $tracking['created_at']) . '</td>';
        				if($tracking['from'] == TrackingSearch::FROM_OTHER_PAGE || $tracking['from'] == TrackingSearch::FROM_OTHER_SITE) {
        					$tr .= '<td title="' . $tracking['referer'] . '" style="font-weight: bold;">' . $sourceDropDown[$tracking['from']] . '</td>';
        				} else {
        					$tr .= '<td style="font-weight: bold;">' . $sourceDropDown[$tracking['from']] . '</td>';
        				}
        				
        				$tr .= '<td>' . $tracking['location'] . '</td>';
        				$tr .= '<td style="color: red;">' . $type[$tracking['type']] . '</td>';
        				$tr .= '<td style="color: blue;">' . ($tracking['category_id'] ? $categoryDropDown[$tracking['category_id']] : '') . '</td>';
        				
        				$content = [];
        				
        				if($tracking['room_no']) {
        					$content[] = $tracking['room_no'] . ' beds';
        				}
        				if($tracking['toilet_no']) {
        					$content[] = $tracking['toilet_no'] . ' bath';
        				}
        				if($tracking['price_min']) {
        					$content[] = '$ min: ' . StringHelper::formatCurrency($tracking['price_min']);
        				}
        				if($tracking['price_max']) {
        					$content[] = '$ max: ' . StringHelper::formatCurrency($tracking['price_max']);
        				}
        				if($tracking['size_min']) {
        					$content[] = 'size min: ' . $tracking['size_min'];
        				}
        				if($tracking['size_max']) {
        					$content[] = 'size max: ' . $tracking['size_max'];
        				}
        				if($tracking['order_by']) {
        					$content[] = 'sort: ' . $mapSort[$tracking['order_by']];
        				}
        				
        				$tr .= '<td>' . implode('<span class="separator"></span>', $content) . '</td>';
        				
        				$tr .= '</tr>';
        				
        				$table .= $tr;
        			}
        			
        			$table .= '</table>';
        			
        			return $table;
    			}		
    		]
        ],
    ]); ?>

</div>
