<?php

namespace frontend\controllers;

use dektrium\user\models\Profile;
use frontend\components\Controller;
use vsoft\news\models\CmsCatalog;
use vsoft\news\models\CmsShow;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\Response;

class NewsController extends Controller
{
    public $layout = 'news';

    public function actions()
    {
        return [
            'review' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if($arrCats = array_values(Yii::$app->params["news"]["widget-category"])){
            $detail = CmsShow::find()->where('catalog_id IN ('.implode($arrCats, ',').')')
                ->orderBy('id DESC')->one();
            $url = Url::to(['news/view', 'id' => $detail->id, 'slug' => $detail->slug, 'cat_id' => $detail->catalog->id, 'cat_slug' => $detail->catalog->slug]);
            $this->redirect($url);
        }
        // Pass url of request
        Yii::$app->meta->add(Yii::$app->request->absoluteUrl);
        return $this->render('index');
    }

    public function actionView($id)
    {
        $detail = CmsShow::findOne($id);
        $user_id = $detail->created_by;
        $author = Profile::findOne($user_id);
        $catalog = CmsCatalog::findOne($detail->catalog_id);

        // add 1 for each click news link
        $click = $detail->click;
        $detail->click = $click + 1;
        $detail->update();

        $this->view->title = $detail->title;
        return $this->render('detail', ['news' => $detail, 'author' => $author, 'catalog' => $catalog]);
    }

    public function actionFindnotfound()
    {
        return $this->render('404');
    }

    public function actionList($cat_id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CmsShow::find()->where('catalog_id = :cat_id', [':cat_id' => $cat_id])
                ->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => Yii::$app->params["news"]["limit"],
            ],
        ]);
        $this->view->title = CmsCatalog::findOne($cat_id)->title;
        return $this->render('list', ['dataProvider' => $dataProvider, 'cat_id' => $cat_id]);
    }

    public function actionGetone()
    {
        $current_id = Yii::$app->request->get('current_id');
        $cat_id = Yii::$app->request->get('cat_id');

        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = CmsShow::find()->asArray()
//            ->innerJoin('profile p', 'cms_show.created_by = p.user_id')
//            ->select(['cms_show.*', 'p.name as author_name', 'p.avatar', 'p.bio'])
            ->where('cms_show.id NOT IN (:id)', [':id' => $current_id])
            ->andWhere('cms_show.catalog_id = :cat_id', [':cat_id' => $cat_id])
            ->andWhere('cms_show.id < :_id', [':_id' => $current_id])
            ->andWhere('cms_show.status = :status', [':status' => 1])
            ->orderBy(['cms_show.id' => SORT_DESC])
            ->one();

        $catalog = CmsCatalog::find()->asArray()->select(['title as catalog_name', 'slug as cat_slug'])->where('id = :id', [':id' => $cat_id])->one();
        $user_id = $model["created_by"];
        $profile = Profile::find()->asArray()->select(['name as author_name', 'avatar', 'bio'])->where('user_id = :uid', [':uid' => $user_id])->one();

        $result = array_merge($profile, $catalog, $model);

        return $result;
    }

}
