<?php
namespace vsoft\buildingProject\models;

use funson86\cms\models\CmsShowSearch;
use yii\data\ActiveDataProvider;
use yii\base\Model;

class BuildingProjectSearch extends BuildingProject {
	
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}
	
	public function search($params)
	{
		$query = BuildingProject::find();
	
		$query->orderBy(['created_at' => SORT_DESC]);
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
	
		if ($this->load($params) && !$this->validate()) {
			return $dataProvider;
		}
	
		$query->andFilterWhere([
			'id' => $this->id,
			'catalog_id' => $this->catalog_id,
		]);
	
		$query->andFilterWhere(['like', 'title', $this->title]);
		
		return $dataProvider;
	}
}