<?php

namespace vsoft\ec\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use vsoft\ec\models\EcTransactionHistory;

/**
 * EcTransactionHistorySearch represents the model behind the search form about `vsoft\ec\models\EcTransactionHistory`.
 */
class EcTransactionHistorySearch extends EcTransactionHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'object_id', 'object_type', 'action_type', 'action_detail', 'charge_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number'],
            [['params'], 'safe'],
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
    public function search($params, $user_id = null)
    {
        $query = EcTransactionHistory::find();

        // add conditions that should always apply here
        if($user_id)
            $query = $query->where('user_id = :u', [':u' => $user_id]);

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
            'user_id' => $this->user_id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'amount' => $this->amount,
            'action_type' => $this->action_type,
            'action_detail' => $this->action_detail,
            'charge_id' => $this->charge_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
