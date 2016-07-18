<?php
namespace frontend\components;

use yii\web\UrlManager;
use frontend\models\MapSearch;

class SearchUrlManager extends UrlManager {
	public $enablePrettyUrl = true;
	public $showScriptName = false;
	public $enableStrictParsing = false;
	
	public function createUrl($params) {
		$action = ($params[0] == '/ad/index1') ? \Yii::t('url', 'nha-dat-ban') : \Yii::t('url', 'nha-dat-cho-thue');
			
		$segments = empty($params['params']) ? ['_'] : explode("/", $params['params']);
		if(strpos($segments[0], '_') === FALSE) {
			$action .= '/' . $segments[0];
		}
		
		$covnertParams = [];
		
		foreach ($params as $k => $param) {
			if($k && ($ck = array_search($k, MapSearch::$fieldsMapping)) !== FALSE) {
				$covnertParams[] = $ck . '_' . $param;
			}
		}
		
		return '/' . $action . '/' . implode('/', $covnertParams);
	}
}
