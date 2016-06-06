<?php

namespace frontend\controllers;

use frontend\components\Controller;
use frontend\models\Profile;
use vsoft\news\models\CmsCatalog;
use vsoft\news\models\CmsShow;
use vsoft\news\models\Status;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Response;

class NewsController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        $this->view->params['noFooter'] = true;
        $this->view->params['menuNews'] = true;
        return parent::beforeAction($action);
    }

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
        // build a DB query to get all News with status = 1
        $query = CmsShow::find()->select(['cms_show.id','cms_show.banner','cms_show.title','cms_show.slug','cms_show.brief', 'cms_show.created_at','cms_show.catalog_id', 'cms_catalog.title as cat_title', 'cms_catalog.slug as cat_slug'])
            ->join('inner join', CmsCatalog::tableName(), 'cms_show.catalog_id = cms_catalog.id')
            ->where('cms_show.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->andWhere('cms_catalog.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->andWhere(['NOT IN', 'cms_show.catalog_id', [1]])
            ->andWhere(['IN', 'cms_show.language_id', [Yii::$app->language]])
            ->orderBy('cms_show.created_at DESC');

        // get the total number of News (but do not fetch the News data yet)
        $count = $query->count();

        // create a pagination object with the total count
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->defaultPageSize = 12;
        // limit the query using the pagination and retrieve the articles
        $news = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()->all();
        $this->view->title = Yii::t('news','News');
        return $this->render('index',['news' => $news, 'pagination' => $pagination]);
    }

    public function actionView($id)
    {
//        $cmsShow = CmsShow::findOne($id);
//        $user_id = $detail->created_by;
//        $author = Profile::findOne($user_id);
//        $catalog = CmsCatalog::findOne($detail->catalog_id);
        $detail = CmsShow::find()->select(['cms_show.id','cms_show.banner','cms_show.title','cms_show.slug','cms_show.brief', 'cms_show.content', 'cms_show.seo_title','cms_show.seo_keywords','cms_show.seo_description', 'cms_show.created_at','cms_show.catalog_id', 'cms_show.click', 'cms_catalog.title as cat_title', 'cms_catalog.slug as cat_slug'])
            ->join('inner join', CmsCatalog::tableName(), 'cms_show.catalog_id = cms_catalog.id')
            ->where('cms_show.id = :id', [':id' => $id])
            ->andWhere('cms_show.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->asArray()->orderBy('cms_show.created_at DESC')->one();
        $click = (int)$detail["click"] + 1;
        if(count($detail) > 0) {
            CmsShow::getDb()->createCommand()
                ->update(CmsShow::tableName(), ['click' => $click], ['id' => $detail["id"]])
                ->execute();
            $this->view->title = $detail["title"];
        }
        return $this->render('detail', ['news' => $detail]);
    }

    public function actionList($cat_id)
    {
        $query = CmsShow::find()->select(['cms_show.id','cms_show.banner','cms_show.title','cms_show.slug','cms_show.brief', 'cms_show.seo_title','cms_show.seo_keywords','cms_show.seo_description', 'cms_show.created_at','cms_show.catalog_id', 'cms_catalog.title as cat_title', 'cms_catalog.slug as cat_slug'])
            ->join('inner join', CmsCatalog::tableName(), 'cms_show.catalog_id = cms_catalog.id')
            ->where('cms_show.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->andWhere('cms_catalog.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->andWhere(['IN', 'cms_show.catalog_id', [$cat_id]])
            ->andWhere(['IN', 'cms_show.language_id', [Yii::$app->language]])
            ->orderBy('cms_show.created_at DESC');
        $count = (int)$query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->defaultPageSize = 5;

        $news = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->asArray()->all();
        if($count > 0){
            $this->view->title = $news[0]["cat_title"];
        }
        else {
            $catalog = CmsCatalog::findOne($cat_id);
            $this->view->title = $catalog->title;
        }

        return $this->render('list', ['news' => $news, 'cat_id' => $cat_id, 'pagination' => $pagination]);
    }

    public function actionGetone()
    {
        $current_id = Yii::$app->request->get('current_id');
        $cat_id = Yii::$app->request->get('cat_id');
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = CmsShow::find()
            ->join('inner join', CmsCatalog::tableName(), 'cms_show.catalog_id = cms_catalog.id')
//            ->innerJoin('profile p', 'cms_show.created_by = p.user_id')
//            ->select(['cms_show.*', 'p.name as author_name', 'p.avatar', 'p.bio'])
            ->where('cms_show.id NOT IN (:id)', [':id' => $current_id])
            ->andWhere('cms_show.catalog_id = :cat_id', [':cat_id' => $cat_id])
            ->andWhere('cms_show.id < :_id', [':_id' => $current_id])
            ->andWhere('cms_show.status = :status', [':status' => 1])
            ->andWhere('cms_catalog.status = :status', [':status' => Status::STATUS_ACTIVE])
            ->asArray()->orderBy(['cms_show.id' => SORT_DESC])
            ->one();

        $catalog = CmsCatalog::find()->asArray()->select(['title as catalog_name', 'slug as cat_slug'])->where('id = :id', [':id' => $cat_id])->one();
        $user_id = $model["created_by"];
        $profile = Profile::find()->asArray()->select(['name as author_name', 'avatar', 'bio'])->where('user_id = :uid', [':uid' => $user_id])->one();

        $result = null;
        if(!empty($profile) && !empty($catalog) && !empty($model))
            $result = array_merge($profile, $catalog, $model);

        return $result;
    }

}
