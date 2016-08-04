<?php
namespace vsoft\ad\models;

use common\models\TrackingSearch as TS;
use yii\db\Query;
use yii\data\ActiveDataProvider;

class TrackingSearchGroup extends TS {
	
	public $alias;
	public $finder_filter;
	
	public function formName() {
		return '';
	}
	
	public function rules()
	{
		return [
			[['alias', 'finder_filter'], 'safe']
		];
	}
	
	public function search($params)
	{
		$query = new Query();
		$query->from("`tracking_search`");
		$query->select(["`alias`", "MAX(`created_at`) `created_at`"]);
		$query->groupBy("alias");

		if(!isset($params['sort'])) {
			$query->orderBy("created_at DESC");
		}
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query
		]);
		
		if ($this->load($params) && !$this->validate()) {
			return $dataProvider;
		}
		
		$dataProvider->sort->attributes['alias'] = [
			'asc' => ['alias' => SORT_ASC],
			'desc' => ['alias' => SORT_DESC]
		];

		if($this->finder_filter !== null && $this->finder_filter !== '') {
			if($this->finder_filter == 0) {
				$query->andWhere("user_id IS NOT NULL");
			} else if($this->finder_filter == 1) {
				$query->andWhere("user_id IS NULL");
			} else {
				$query->andFilterWhere(['like', 'alias', $this->alias]);
			}
		}
		
		return $dataProvider;
	}
}