<?php

namespace vsoft\ad\models;

use Yii;
use yii\data\ActiveDataProvider;
use common\models\AdCategory as AC;

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
class AdCategory extends AC
{
	const TEMPLATE_SUGGEST_LIST = 1;
	const TEMPLATE_COST_MIN_MAX = 2;

	const APPLY_TO_TYPE_SELL = 1;
	const APPLY_TO_TYPE_RENT = 2;
	const APPLY_TO_TYPE_BOTH = 3;
	
	public static function templateMap() {
		return [
			self::TEMPLATE_SUGGEST_LIST => 'loai-duan',
			self::TEMPLATE_COST_MIN_MAX => 'min-max',
		];
	}
	
	public static function templateLabelMap() {
		return [
			self::TEMPLATE_SUGGEST_LIST => 'Suggest projects list',
			self::TEMPLATE_COST_MIN_MAX => 'Cost min max',
		];
	}

	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'name' => 'Loại tin',
			'apply_to_type' => 'Apply To Type',
			'order' => 'Order',
			'status' => 'Status',
		];
	}
	
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
