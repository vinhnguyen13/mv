<?php

namespace vsoft\express\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\express\models\LcPricing;

/**
 * LcPricingSearch represents the model behind the search form about `vsoft\express\models\LcPricing`.
 */
class LcPricingSearch extends LcPricing
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'apart_type_id', 'created_by', 'updated_by'], 'integer'],
            [['area', 'monthly_rates', 'daily_rates'], 'number'],
            [['description', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = LcPricing::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'apart_type_id' => $this->apart_type_id,
            'area' => $this->area,
            'monthly_rates' => $this->monthly_rates,
            'daily_rates' => $this->daily_rates,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
