<?php

namespace frontend\controllers;

use dektrium\user\models\Profile;
use vsoft\news\models\CmsShow;
use Yii;
use yii\web\Response;

class NewsController extends \yii\web\Controller
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
        return $this->render('index');
    }

    public function actionView($id)
    {
        $detail = CmsShow::findOne($id);
        $user_id = $detail->created_by;
        $author = Profile::findOne($user_id);

        return $this->render('detail', ['news' => $detail, 'author' => $author]);
    }

    public function actionList($cat_id)
    {
        $list = CmsShow::find()->where('catalog_id = :cat_id', [':cat_id' => $cat_id])
            ->orderBy(['id' => SORT_DESC])->all();
        return $this->render('list', ['list_news' => $list, 'cat_id' => $cat_id]);
    }

    public function actionGetone($current_id, $cat_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
//        $sql = "SELECT * FROM cms_show WHERE id NOT IN (" . $current_id . ") and id < " . $current_id . " AND catalog_id = " . $cat_id . " ORDER BY id DESC LIMIT 1 ";
//        $model = CmsShow::findBySql($sql)->one();
        $model = CmsShow::find()
            ->innerJoin('profile','cms_show.created_by = profile.user_id')
            ->select(['cms_show.*','profile.*'])
            ->where('cms_show.id NOT IN (:id)', [':id' => $current_id])
            ->andWhere('cms_show.catalog_id = :cat_id', [':cat_id' => $cat_id])
            ->andWhere('cms_show.id < :_id', [':_id' => $current_id])
            ->andWhere('cms_show.status = :status', [':status' => 1])
            ->orderBy(['cms_show.id' => SORT_DESC])
            ->one();
        return $model;
    }

}
