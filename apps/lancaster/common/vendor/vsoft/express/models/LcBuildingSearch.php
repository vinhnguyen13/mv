<?php

namespace vsoft\express\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\express\models\LcBuilding;

/**
 * LcBuildingSearch represents the model behind the search form about `vsoft\express\models\LcBuilding`.
 */
class LcBuildingSearch extends LcBuilding
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'isbooking', 'floor'], 'integer'],
            [['building_name', 'address', 'phone', 'fax', 'email', 'hotline', 'description', 'created_at', 'updated_at'], 'safe'],
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
        $query = LcBuilding::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'isbooking' => $this->isbooking,
            'floor' => $this->floor,
        ]);

        $query->andFilterWhere(['like', 'building_name', $this->building_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'fax', $this->fax])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'hotline', $this->hotline])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
