<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdCategoryBase;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "ad_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $order
 * @property integer $status
 *
 * @property AdProduct[] $adProducts
 */
class AdCategory extends AdCategoryBase
{
	public function search($params)
	{
		$query = self::find();
	
		$query->orderBy(['order' => SORT_ASC]);
	
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
