<?php
namespace vsoft\ad\models;

use yii\data\ActiveDataProvider;

class AdCitySearch extends AdCity {
	public function rules()
	{
		return [
				[['name', 'code', 'slug', 'pre'], 'safe']
		];
	}
	
	function search($params) {
		$query = self::find();
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		
		if ($this->load($params) && !$this->validate()) {
			return $dataProvider;
		}
		
		$query->andFilterWhere(['like', 'name', $this->name]);
		$query->andFilterWhere(['like', 'code', $this->code]);
		$query->andFilterWhere(['like', 'slug', $this->slug]);
		$query->andFilterWhere(['like', 'pre', $this->pre]);
		
		return $dataProvider;
	}
}