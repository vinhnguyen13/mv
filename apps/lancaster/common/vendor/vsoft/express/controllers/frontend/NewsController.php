<?php

namespace vsoft\express\controllers\frontend;

use funson86\cms\models\CmsCatalog;
use funson86\cms\models\CmsShow;
use funson86\cms\models\Status;
use Yii;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class NewsController extends \yii\web\Controller
{
    public $layout = '@app/views/layouts/news';
    public function actionIndex()
    {
        $ids = CmsCatalog::getArraySubCatalogId(0, CmsCatalog::find()->where([
            'surname' => 'NEWS',
        ])->asArray()->all());
        $query = CmsShow::find();
        $query->where([
            'status' => Status::STATUS_ACTIVE,
            'catalog_id' => $ids,
        ]);

        $pagination = new Pagination([
            'defaultPageSize' => isset(\Yii::$app->params['cmsListPageCount']) ? Yii::$app->params['cmsListPageCount'] : 30,
            'totalCount' => $query->count(),
        ]);

        $news = $query->orderBy(['created_at' => SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', ['news'=>$news, 'pagination' => $pagination]);
    }

    public function actionDetail()
    {
        $id = \Yii::$app->request->get('id');
        if (!$id) $this->goHome();
        //$this->layout = 'column2';

        $detail = CmsShow::findOne($id);

        /**
         * Related post
         */

        $catids = ArrayHelper::map(CmsCatalog::find()->where([
            'surname' => 'NEWS',
        ])->asArray()->all(),
        'id', 'id');

        $relatedPost = CmsShow::find()->where([
            'status' => Status::STATUS_ACTIVE,
            'catalog_id' => $catids,
        ])->andWhere(['<>', 'id', $id])->all();

        return $this->render('detail', ['detail'=>$detail, 'relatedPost'=>$relatedPost]);
    }

}
