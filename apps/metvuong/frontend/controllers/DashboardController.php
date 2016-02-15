<?php

namespace frontend\controllers;
use dektrium\user\Mailer;
use frontend\models\User;
use frontend\models\ProfileForm;
use vsoft\express\components\ImageHelper;
use Yii;
use yii\db\mssql\PDO;
use yii\helpers\Url;
use vsoft\news\models\CmsShow;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\View;
use frontend\components\Controller;

class DashboardController extends Controller
{
    public $layout = '@app/views/layouts/layout';

    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/member/login']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->redirect('/dashboard/statistics');
    }

    public function actionStatistics()
    {
        return $this->render('statistics/index', [
        ]);
    }

    public function actionNotification()
    {
        return $this->render('notification/index', [
        ]);
    }

    public function actionAds()
    {
        return $this->render('ads/index', [
        ]);
    }

    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'updateprofile',
        ]);

        $model = $model->loadProfile();
        if(!$model->avatar) {
            $model->avatar  = 'default-avatar.jpg';
        }

        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                $model->load($post);
                $model->validate();
                if (!$model->hasErrors()) {
                    if($post["type"])
                        $model->$post["type"] = strip_tags(html_entity_decode($post["txt"]));
                    $res = $model->updateProfile();
                    return ['statusCode'=>true];
                }else{
                    return ['statusCode'=> false, 'parameters' => $model->errors];
                }
            }
            return $this->renderAjax('user/profile', [
                'model' => $model
            ]);
        }
        return $this->render('user/profile', [
            'model' => $model
        ]);
    }

    public function actionPassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Yii::createObject([
            'class'    => ProfileForm::className(),
            'scenario' => 'password',
        ]);

        if(Yii::$app->request->isAjax) {
            if(Yii::$app->request->isPost){
                Yii::$app->response->format = Response::FORMAT_JSON;
                $post = Yii::$app->request->post();
                $model->load($post);
                $model->validate();
                if (!$model->hasErrors()) {
                    $res = $model->resetPass();
                    return ['statusCode'=>$res];
                }else{
                    return ['statusCode'=> false, 'parameters' => $model->errors];
                }
            }
            return $this->renderAjax('user/password', [
                'model' => $model
            ]);
        }

        return $this->render('user/password', [
            'model'=>$model
        ]);
    }


    public function actionAvatar($folder = 'avatar', $resizeForAds = false) {
        $model = Yii::createObject([
            'class' => ProfileForm::className(),
            'scenario' => 'updateavatar',
        ]);

        if($_FILES) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $image = UploadedFile::getInstanceByName('upload');
            $dir = \Yii::getAlias('@store') . "/$folder";
            $uniqid = uniqid();
            $extension = pathinfo($image->name, PATHINFO_EXTENSION);

            $orginal = 'u_'. Yii::$app->user->id. '_' .$uniqid . '.' . $extension;
            $thumbnail = 'u_'. Yii::$app->user->id. '_' .$uniqid . '.thumb.' . $extension;

            $orginalPath = $dir . '/' . $orginal;
            $thumbnailPath = $dir . '/' . $thumbnail;

            $image->saveAs($orginalPath);

            $options = ($resizeForAds) ? [] : ['thumbWidth' => 120, 'thumbHeight' => 120];
            $imageHelper = new ImageHelper($orginalPath, $options);
            $imageHelper->makeThumb(false, $thumbnailPath);

            $response['files'][] = [
                'url'           => Url::to("/store/$folder/" .$orginal),
                'thumbnailUrl'  => Url::to("/store/$folder/" .$thumbnail),
                'name'          => $orginal,
                'type'          => $image->type,
                'size'          => $image->size,
                'deleteUrl'     => Url::to(['dashboard/delete-image', 'orginal' => $orginal, 'thumbnail' => $thumbnail, 'folder' => $folder]),
                'deleteType'    => 'DELETE',
                'deleteLater'	=> 0,
            ];
            $model->updateAvatar($orginal);
            return $response;
        }
        return $response['files'] = array();
    }

    public function actionDeleteImage($orginal, $thumbnail, $deleteLater = false, $folder = 'avatar') {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(!$deleteLater) {
            $dir = \Yii::getAlias('@store') . DIRECTORY_SEPARATOR . $folder;
            if($orginal != "default-avatar.jpg" && $thumbnail != "default-avatar.thumb.jpg")
            {
                if(file_exists($dir . DIRECTORY_SEPARATOR . $orginal))
                    unlink($dir . DIRECTORY_SEPARATOR . $orginal);
                if(file_exists($dir . DIRECTORY_SEPARATOR . $thumbnail))
                    unlink($dir . DIRECTORY_SEPARATOR . $thumbnail);
                $model = Yii::createObject([
                    'class' => ProfileForm::className(),
                    'scenario' => 'updateavatar',
                ]);

                return $model->updateAvatar(null);
            }
        }
        return false;
    }

}
