<?php

namespace vsoft\ad\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\ad\models\AdProductReport;

/**
 * AdProductReportSearch represents the model behind the search form about `vsoft\ad\models\AdProductReport`.
 */
class AdProductReportSearch extends AdProductReport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'type', 'status', 'report_at'], 'integer'],
            [['description', 'ip'], 'safe'],
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
        $query = AdProductReport::find();

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
            'type' => $this->type,
            'status' => $this->status,
            'report_at' => $this->report_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'ip', $this->ip]);

        return $dataProvider;
    }
}
