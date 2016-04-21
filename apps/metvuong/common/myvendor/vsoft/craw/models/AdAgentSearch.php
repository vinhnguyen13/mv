<?php

namespace vsoft\craw\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\craw\models\AdAgent;

/**
 * AdAgentSearch represents the model behind the search form about `vsoft\craw\models\AdAgent`.
 */
class AdAgentSearch extends AdAgent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rating', 'source', 'type', 'updated_at'], 'integer'],
            [['name', 'address', 'mobile', 'phone', 'fax', 'email', 'website', 'tax_code', 'working_area'], 'safe'],
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
        $query = AdAgent::find();

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
            'rating' => $this->rating,
            'source' => $this->source,
            'type' => $this->type,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'website', $this->website])
            ->andFilterWhere(['like', 'tax_code', $this->tax_code])
            ->andFilterWhere(['like', 'working_area', $this->working_area]);

        return $dataProvider;
    }
}
