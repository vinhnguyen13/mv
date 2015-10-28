<?php

namespace vsoft\news\controllers;

use funson86\cms\controllers\backend\CmsShowController;
use vsoft\news\models\CmsShow;
use vsoft\news\models\CmsShowSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;
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

        if (Yii::$app->request->get('catalog_id')) {
            $model->catalog_id = Yii::$app->request->get('catalog_id');
        }

        if ($model->load(Yii::$app->request->post())) {
            $upload_image = UploadedFile::getInstance($model, 'banner');
            if (!empty($upload_image)) {
                $image_name = Inflector::slug($upload_image->baseName);
                $model->banner = $image_name . '_' . date('mdYhis') . '.' . $upload_image->extension;
            }
            if ($model->banner) {
                $upload_image->saveAs(Yii::getAlias('@store'). '/news/show/' . $model->banner);
            }
//            $model->created_by = Yii::$app->user->id;
//            $model->updated_by = Yii::$app->user->id;
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

        if ($model->load(Yii::$app->request->post())) {
            $upload_image = UploadedFile::getInstance($model, 'banner');
            if (!empty($upload_image)) {
                $image_name = Inflector::slug($upload_image->baseName);
                $model->banner = $image_name . '_' . date('mdYhis') . '.' . $upload_image->extension;
            }
            if ($model->banner) {
                $upload_image->saveAs(Yii::getAlias('@store'). '/news/show/' . $model->banner);
            } else {
                $model->banner = $oldBanner;
            }
//            if($model->created_by === 0)
//                $model->created_by = Yii::$app->user->id;
//            $model->updated_by = Yii::$app->user->id;

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
}
