<?php

namespace vsoft\news\controllers;

use funson86\cms\controllers\backend\CmsShowController;
use vsoft\news\models\CmsShow;
use Yii;
use yii\web\UploadedFile;

/**
 * CmsController implements the CRUD actions for CmsShow model.
 */
class CmsController extends CmsShowController
{

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
                $image_name = $upload_image->baseName;
                $model->banner = $image_name . '_' . date('mdYhis') . '.' . $upload_image->extension;
            }
            if ($model->banner) {
                $upload_image->saveAs(Yii::getAlias('@store'). '/news/show/' . $model->banner);
            }
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

        $model = $this->findModel($id);
        $oldBanner = $model -> banner;

        if ($model->load(Yii::$app->request->post())) {
            $upload_image = UploadedFile::getInstance($model, 'banner');
            if (!empty($upload_image)) {
                $image_name = $upload_image->baseName;
                $model->banner = $image_name . '_' . date('mdYhis') . '.' . $upload_image->extension;
            }
            if ($model->banner) {
                $upload_image->saveAs(Yii::getAlias('@store'). '/news/show/' . $model->banner);
            } else {
                $model->banner = $oldBanner;
            }

            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

}
