<?php

namespace vsoft\ad\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\ad\models\AdProductSaved;

/**
 * AdProductSavedSearch represents the model behind the search form about `vsoft\ad\models\AdProductSaved`.
 */
class AdProductSavedSearch extends AdProductSaved
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'saved_at'], 'integer'],
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
        $query = AdProductSaved::find();

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
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'saved_at' => $this->saved_at,
        ]);

        return $dataProvider;
    }

    public function search2($params, $from, $to)
    {
        $query = AdProductSaved::find();

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
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
        ])->andFilterWhere(['between', 'saved_at', $from, $to]);


        return $dataProvider;
    }
}
