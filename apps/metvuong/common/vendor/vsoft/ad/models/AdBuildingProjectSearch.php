<?php

namespace common\vendor\vsoft\ad\models;

use yii\data\ActiveDataProvider;
use yii\base\Model;
class AdBuildingProjectSearch extends AdBuildingProject {

	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}
	
	public function search($params)
	{
		$query = AdBuildingProject::find();
	
		$query->orderBy(['created_at' => SORT_DESC]);
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
	
		if ($this->load($params) && !$this->validate()) {
			return $dataProvider;
		}
	
		$query->andFilterWhere([
			'id' => $this->id
		]);
	
		$query->andFilterWhere(['like', 'name', $this->name]);
	
		return $dataProvider;
	}
}