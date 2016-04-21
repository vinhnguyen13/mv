<?php

namespace api\modules\v1\controllers;

use api\modules\v1\components\Controller;
use dektrium\user\models\User;
use \Yii;
use OAuth2\Response;
use yii\helpers\ArrayHelper;

/**
 * Country Controller API
 *
 * @author Budi Irawan <deerawan@gmail.com>
 */
class TestController extends \yii\rest\Controller
{
//    public function behaviors()
//    {
//        return ArrayHelper::merge(
//            parent::behaviors(),
//            [
//                'as access' => [
//                    'class' => 'api\modules\v1\components\AccessControl',
//                    'allowActions' => [/*'site/login', 'site/error'*/]
//                ],
//            ]
//        );
//    }

    public function actionUsers()
    {
        $users = User::find()->select(['username', 'email'])->limit(10)->asArray()->all();
        return $users;
    }

    public function actionUpload()
    {
        if (isset($_FILES['avatar'])){
            if($_FILES['avatar']['type'] == "image/jpeg"
                || $_FILES['avatar']['type'] == "image/png"
                || $_FILES['avatar']['type'] == "image/gif"){
                $path = "../../uploads/";
                $tmp_name = $_FILES['avatar']['tmp_name'];
                $file_name = $_FILES['avatar']['name'];
                $file_type = $_FILES['avatar']['type'];
                $file_size = $_FILES['avatar']['size'];
                $array = explode('.', $file_name);
                $file_extension=end($array);
                $avatar=strtotime('now').'.'.$file_extension;
                move_uploaded_file($tmp_name, $path . $file_name);


                $fileOrigin = $path.$file_name;
                if(file_exists($fileOrigin)){
                    $pathinfo = pathinfo($fileOrigin);
                    $fileNew = $pathinfo['dirname']."/".$pathinfo['filename'].".thumb200x0.".$pathinfo['extension'];
                    $image = Yii::$app->image->load($fileOrigin);
                    $image->resize(200)->sharpen(10)->save($fileNew);
                }
                echo "<pre>";
                print_r(Yii::getAlias('@rootapp'));
                echo "</pre>";
                exit;

            }
        }
    }

}


