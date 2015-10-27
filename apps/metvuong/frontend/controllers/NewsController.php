<?php

namespace frontend\controllers;

use dektrium\user\models\Profile;
use vsoft\news\models\CmsCatalog;
use vsoft\news\models\CmsShow;
use Yii;
use yii\data\ActiveDataProvider;
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
        // Pass url of request
        Yii::$app->meta->add(Yii::$app->request->absoluteUrl);
        return $this->render('index');
    }

    public function actionView($id)
    {
        $detail = CmsShow::findOne($id);
        $user_id = $detail->created_by;
        $author = Profile::findOne($user_id);

        // add 1 for each click news link
        $click = $detail->click;
        $detail->click = $click + 1;
        $detail->update();

        $this->view->title = $detail->title;
        return $this->render('detail', ['news' => $detail, 'author' => $author]);
    }

    public function actionList($cat_id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CmsShow::find()->where('catalog_id = :cat_id', [':cat_id' => $cat_id])
                                ->orderBy('id DESC'),
            'pagination' => [
                'pageSize' => 9,
            ],
        ]);
        $this->view->title = CmsCatalog::findOne($cat_id)->title;
        return $this->render('list', ['dataProvider' => $dataProvider, 'cat_id' => $cat_id]);
    }

    public function actionGetone($current_id, $cat_id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
//        $sql = "SELECT * FROM cms_show WHERE id NOT IN (" . $current_id . ") and id < " . $current_id . " AND catalog_id = " . $cat_id . " ORDER BY id DESC LIMIT 1 ";
//        $model = CmsShow::findBySql($sql)->one();
        $model = CmsShow::find()
            ->innerJoin('profile', 'cms_show.created_by = profile.user_id')
            ->select(['cms_show.*', 'profile.*'])
            ->where('cms_show.id NOT IN (:id)', [':id' => $current_id])
            ->andWhere('cms_show.catalog_id = :cat_id', [':cat_id' => $cat_id])
            ->andWhere('cms_show.id < :_id', [':_id' => $current_id])
            ->andWhere('cms_show.status = :status', [':status' => 1])
            ->orderBy(['cms_show.id' => SORT_DESC])
            ->one();
        // add 1 for each click news link
        $click = $model->click;
        $model->click = $click + 1;
        $model->update();

        return $model;
    }

}
