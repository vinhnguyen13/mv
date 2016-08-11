<?php

namespace vsoft\ad\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\ad\models\MarkEmail;

/**
 * MarkEmailSearch represents the model behind the search form about `vsoft\ad\models\MarkEmail`.
 */
class MarkEmailSearch extends MarkEmail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'safe'],
            [['type', 'count', 'status', 'send_time'], 'integer'],
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
        $query = MarkEmail::find();

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
            'type' => $this->type,
            'count' => $this->count,
            'status' => $this->status,
            'send_time' => $this->send_time,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
