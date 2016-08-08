<?php

namespace vsoft\coupon\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\coupon\models\CouponHistory;

/**
 * CouponHistorySearch represents the model behind the search form about `vsoft\coupon\models\CouponHistory`.
 */
class CouponHistorySearch extends CouponHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'cp_code_id', 'cp_event_id', 'created_at'], 'integer'],
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
        $query = CouponHistory::find();

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
            'user_id' => $this->user_id,
            'cp_code_id' => $this->cp_code_id,
            'cp_event_id' => $this->cp_event_id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
