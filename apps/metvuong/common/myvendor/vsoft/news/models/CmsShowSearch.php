<?php

namespace vsoft\news\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * CmsShowSearch represents the model behind the search form about `funson86\cms\models\CmsShow`.
 */
class CmsShowSearch extends \funson86\cms\models\CmsShowSearch
{
    public function rules()
    {
        return array_merge(parent::rules(),[
            [['created_by', 'updated_by'], 'integer']
        ]);
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
        //get list parent catalog
        $parentCatalog = ArrayHelper::map(CmsCatalog::get(Yii::$app->params['newsCatID'], CmsCatalog::find()->asArray()->all()), 'id', 'label');
        $keys = array_keys($parentCatalog);
        array_push($keys, Yii::$app->params['homepageCatID']);

        $query = CmsShow::find()->where('catalog_id in ('. implode (", ", $keys) . ')');

        $query->orderBy(['created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'catalog_id' => $this->catalog_id,
            'click' => $this->click,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
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
