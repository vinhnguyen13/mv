<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use vsoft\ad\models\AdProduct;
use vsoft\ad\models\AdCategoryGroup;
use vsoft\express\components\StringHelper;
use vsoft\ad\models\TrackingSearch;
use frontend\models\MapSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CmsShowSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCss('.separator {margin: 0 8px;} .summary {position: absolute; right: 0px; top: -20px;} .cms-show-index {padding-top: 40px; position: relative;} .filter-col {margin-right: 12px;} .container {max-width: none; width: auto;} .summary {float: right;font-size: 20px;margin-top: 28px;} .title {float: left;} .min {width: 100px; display: inline-block;} table {white-space: nowrap;}');
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
<div style="position: relative; z-index: 1;">
	<a style="font-size: 20px; display: inline-block;" href="<?= Url::to(['/ad/tracking-search']) ?>">Show all</a>
	<span style="margin: 12px;"></span>
	<a style="font-size: 20px; display: inline-block;" href="<?= Url::to(['/ad/tracking-search/group']) ?>">Group theo đối tượng</a>
</div>
<div class="cms-show-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
        	'alias',
        	[
        		'format' => 'raw',
        		'attribute' => 'contents',
        		'value' => function($model) use($type, $categoryDropDown, $sourceDropDown, $mapSort) {
        			$contents = explode(' ||| ', $model['contents']);
        			
        			$return = '<ul>';
        			
        			foreach ($contents as $content) {
        				$li = [];
        				
        				$content = explode(' |*| ', $content);
        				$referer = '';
        				
        				foreach ($content as $field) {
        					$field = explode(' : ', $field);
        					
        					if($field[0] == 'l') {
        						$li[] = '<span class="content-value">' . $field[1] . '</span>';
        					} else if($field[0] == 't') {
        						$li[] = '<span class="content-value" style="color: red;">' . $type[$field[1]] . '</span>';
        					} else if($field[0] == 'ci') {
        						$li[] = '<span class="content-value" style="color: blue;">' . $categoryDropDown[$field[1]] . '</span>';
        					} else if($field[0] == 'rn') {
        						$li[] = '<span class="content-value">' .$field[1] . ' phòng ngủ</span>';
        					} else if($field[0] == 'tn') {
        						$li[] = '<span class="content-value">' .$field[1] . ' phòng tắm</span>';
        					} else if($field[0] == 'pi') {
        						$li[] = '<span class="content-value">price min: ' . StringHelper::formatCurrency($field[1]) . '</span>';
        					} else if($field[0] == 'pa') {
        						$li[] = '<span class="content-value">price max: ' . StringHelper::formatCurrency($field[1]) . '</span>';
        					} else if($field[0] == 'si') {
        						$li[] = '<span class="content-value">size min: ' . $field[1] . '</span>';
        					} else if($field[0] == 'sa') {
        						$li[] = '<span class="content-value">size max: ' . $field[1] . '</span>';
        					} else if($field[0] == 'o') {
        						$li[] = '<span class="content-value">sort: ' . $mapSort[$field[1]] . '</span>';
        					} else if($field[0] == 'ca') {
        						$li[] = '<span class="content-value">' . date("h:i:s d-m-Y", $field[1]) . '</span>';
        					} else if($field[0] == 'f') {
        						if($field[1] == TrackingSearch::FROM_HOME || $field[1] == TrackingSearch::FROM_DIRECT || $field[1] == TrackingSearch::FROM_IN_PAGE || $field[1] == TrackingSearch::FROM_QUICK_SEARCH) {
        							$li[] = '<span class="content-value" style="font-weight: bold; width: 110px; display: inline-block;">' . $sourceDropDown[$field[1]] . '</span>';
        						} else {
        							$li[] = '<span title="' . $referer . '" class="content-value" style="font-weight: bold; width: 110px; display: inline-block;">' . $sourceDropDown[$field[1]] . '</span>';
        						}
        					} else if($field[0] == 'r') {
        						$referer = $field[1];
        					}
        				}
        				
        				$return .= '<li>' . implode('<span class="separator"></span>', $li) . '</li>';
        			}
        			
        			$return .= '</ul>';
        			
        			return $return;
        		}
    		]
        ],
    ]); ?>

</div>
