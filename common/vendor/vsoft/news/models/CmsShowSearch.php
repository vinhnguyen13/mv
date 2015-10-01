<?php

namespace vsoft\news\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use funson86\cms\models\CmsShow;

/**
 * CmsShowSearch represents the model behind the search form about `funson86\cms\models\CmsShow`.
 */
class CmsShowSearch extends CmsShow
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'catalog_id', 'click', 'status', 'created_at', 'updated_at', 'create_by', 'update_by'], 'integer'],
            [['title', 'slug', 'surname', 'brief', 'content', 'seo_title', 'seo_keywords', 'seo_description', 'banner', 'template_show', 'author'], 'safe'],
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
        $query = CmsShow::find();

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
            'catalog_id' => $this->catalog_id,
            'click' => $this->click,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'create_by' => $this->create_by,
            'update_by' => $this->update_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'surname', $this->surname])
            ->andFilterWhere(['like', 'brief', $this->brief])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'seo_title', $this->seo_title])
            ->andFilterWhere(['like', 'seo_keywords', $this->seo_keywords])
            ->andFilterWhere(['like', 'seo_description', $this->seo_description])
            ->andFilterWhere(['like', 'banner', $this->banner])
            ->andFilterWhere(['like', 'template_show', $this->template_show])
            ->andFilterWhere(['like', 'author', $this->author]);

        return $dataProvider;
    }
}
