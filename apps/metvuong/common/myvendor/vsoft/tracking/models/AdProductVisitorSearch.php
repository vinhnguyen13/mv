<?php

namespace vsoft\tracking\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\tracking\models\base\AdProductVisitor;

/**
 * AdProductVisitorSearch represents the model behind the search form about `vsoft\tracking\models\AdProductVisitor`.
 */
class AdProductVisitorSearch extends AdProductVisitor
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'user_id', 'product_id', 'time', 'count', 'device'], 'safe'],
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
        $query = AdProductVisitor::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
            ->andFilterWhere(['like', 'product_id', $this->product_id])
            ->andFilterWhere(['like', 'time', $this->time])
            ->andFilterWhere(['like', 'count', $this->count])
            ->andFilterWhere(['like', 'device', $this->device]);

        return $dataProvider;
    }

    public function search2($params, $from, $to)
    {
        $query = AdProductVisitor::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'user_id', $this->user_id])
//            ->andFilterWhere(['like', 'product_id', $this->product_id])
            ->andFilterWhere(['between', 'time', $from, $to])
            ->andFilterWhere(['like', 'count', $this->count])
            ->andFilterWhere(['like', 'device', $this->device]);
        $query->andWhere(['product_id' => [intval($this->product_id)]]);

        return $dataProvider;
    }

}
