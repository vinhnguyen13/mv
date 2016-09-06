<?php

namespace vsoft\news\controllers;

use funson86\cms\controllers\backend\CmsShowController;
use vsoft\express\components\AdImageHelper;
use vsoft\news\models\CmsShow;
use vsoft\news\models\CmsShowSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use yii\image\drivers\Image;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * CmsController implements the CRUD actions for CmsShow model.
 */
class CmsController extends CmsShowController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }

    public function findModel1($id)
    {
        if (($model = CmsShow::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists all CmsShow models.
     * @return mixed
     */
    public function actionIndex()
    {
        //if(!Yii::$app->user->can('viewYourAuth')) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));

        $searchModel = new CmsShowSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        //if(!Yii::$app->user->can('viewYourAuth')) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));

        return $this->render('view', [
            'model' => $this->findModel1($id),
        ]);
    }

    public function actionCreate()
    {
        //if(!Yii::$app->user->can('createYourAuth')) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));

        $model = new CmsShow();
        $model->loadDefaultValues();
        $model->publish_time = date('d-m-Y', time());

        if (Yii::$app->request->get('catalog_id')) {
            $model->catalog_id = Yii::$app->request->get('catalog_id');
        }

        if ($model->load(Yii::$app->request->post())) {
            $upload_image = UploadedFile::getInstance($model, 'banner');
            if (!empty($upload_image)) {
                $image_name = Inflector::slug($upload_image->baseName);
                $model->banner = 'news_' . strtolower($image_name) . '_' . date('mdY') . '.' . $upload_image->extension;
            }
            if ($model->banner) {
                $upload_image->saveAs(Yii::getAlias('@store'). '/news/show/' . $model->banner);
            }
//            $model->created_by = Yii::$app->user->id;
//            $model->updated_by = Yii::$app->user->id;
            $model->publish_time = !empty($model->publish_time) ? strtotime($model->publish_time) : date('d-m-Y', time());
            $model->save();
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        //if(!Yii::$app->user->can('updateYourAuth')) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));

        $model = $this->findModel1($id);
        $oldBanner = $model->banner;
        if(!empty($model->publish_time)){
            $model->publish_time = date('d-m-Y', $model->publish_time);
        }

        if(count($model) <= 0){
            throw new NotFoundHttpException("Not found");
        }

        if ($model->load(Yii::$app->request->post())) {
            $upload_image = UploadedFile::getInstance($model, 'banner');
            if (!empty($upload_image)) {
                $image_name = Inflector::slug($upload_image->baseName);
                $model->banner = strtolower($image_name) . '_' . time() . '.' . $upload_image->extension;
            }
            if ($model->banner) {
                $filePath = Yii::getAlias('@store'). '/news/show/' . $model->banner;
                $upload_image->saveAs($filePath);
                CmsShow::saveThumbnail($filePath, [480, 360]);
            } else {
                $model->banner = $oldBanner;
            }
//            if($model->created_by === 0)
//                $model->created_by = Yii::$app->user->id;
//            $model->updated_by = Yii::$app->user->id;
            $model->publish_time = strtotime($model->publish_time);
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        //if(!Yii::$app->user->can('deleteYourAuth')) throw new ForbiddenHttpException(Yii::t('app', 'No Auth'));

        $this->findModel1($id)->delete();
        /*$model = $this->findModel($id);
        $model->status = Status::STATUS_DELETED;
        $model->save();*/

        return $this->redirect(['index']);
    }

    public function actionUpdateHotnews($cms_id, $stage)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cms = CmsShow::findOne($cms_id);
        if(count($cms)> 0)
        {
            $cms->hot_news = $stage;
            $cms->update();
            return ['title' => $cms->title];
        }
        return false;
    }
}
