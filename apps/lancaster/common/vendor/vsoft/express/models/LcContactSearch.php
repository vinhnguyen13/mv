<?php

namespace vsoft\express\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\express\models\LcContact;

/**
 * LcContactSearch represents the model behind the search form about `vsoft\express\models\LcContact`.
 */
class LcContactSearch extends LcContact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'address', 'title', 'message', 'ip', 'agent', 'browser_type', 'browser_name', 'browser_version', 'platform', 'created_at'], 'safe'],
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
        $query = LcContact::find();

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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'browser_type', $this->browser_type])
            ->andFilterWhere(['like', 'browser_name', $this->browser_name])
            ->andFilterWhere(['like', 'browser_version', $this->browser_version])
            ->andFilterWhere(['like', 'platform', $this->platform]);

        return $dataProvider;
    }
}
