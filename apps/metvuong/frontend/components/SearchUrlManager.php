<?php
namespace frontend\components;

use yii\web\UrlManager;
use frontend\models\MapSearch;
use vsoft\ad\models\AdCategoryGroup;
use yii\helpers\Url;

class SearchUrlManager extends UrlManager {
	public $enablePrettyUrl = true;
	public $showScriptName = false;
	public $enableStrictParsing = false;
	
	public function createUrl($params) {
		$query = $_SERVER['QUERY_STRING'];
		$action = ($params[0] == '/ad/index1') ? \Yii::t('url', 'nha-dat-ban') : \Yii::t('url', 'nha-dat-cho-thue');
		$segmentParams = empty($params['params']) ? MapSearch::$defaultSlug : $params['params'];
		$fieldsMapping = MapSearch::fieldsMapping();
		$pageParam = array_search('page', $fieldsMapping);
		$pattern = '/' . $pageParam . '_[0-9]+$/';
		
		if(preg_match($pattern, $segmentParams)) {
			$segmentParams = preg_replace('/' . $pageParam . '_[0-9]+$/', $pageParam . '_' . $params['page'], $segmentParams);
		} else {
			$segmentParams .= '/' . $pageParam . '_' . $params['page'];
		}
		
		if($query) {
			$segmentParams .= '?' . $query;
		}
		
		return '/' . $action . '/' . $segmentParams;
	}
}
