<?php

namespace vsoft\ad\models;

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

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
	
		if ($this->load($params) && !$this->validate()) {
			return $dataProvider;
		}
	
		$query->andFilterWhere([
			'click' => $this->click,
		]);
	
		$query->andFilterWhere(['like', 'name', $this->name]);
//        $query->orderBy(['created_at' => SORT_DESC]);

		return $dataProvider;
	}
}