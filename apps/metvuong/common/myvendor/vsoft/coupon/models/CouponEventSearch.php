<?php

namespace vsoft\coupon\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\coupon\models\CouponEvent;

/**
 * CouponEventSearch represents the model behind the search form about `vsoft\coupon\models\CouponEvent`.
 */
class CouponEventSearch extends CouponEvent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'created_by'], 'integer'],
            [['name', 'description'], 'safe'],
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
        $query = CouponEvent::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
