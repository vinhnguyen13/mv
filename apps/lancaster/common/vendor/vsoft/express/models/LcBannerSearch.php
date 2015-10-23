<?php

namespace vsoft\express\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\express\models\LcBanner;

/**
 * LcBannerSearch represents the model behind the search form about `vsoft\express\models\LcBanner`.
 */
class LcBannerSearch extends LcBanner
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'height', 'width', 'created_by', 'updated_by'], 'integer'],
            [['name', 'description', 'url', 'created_at', 'updated_at'], 'safe'],
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
        $query = LcBanner::find();

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
            'height' => $this->height,
            'width' => $this->width,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
