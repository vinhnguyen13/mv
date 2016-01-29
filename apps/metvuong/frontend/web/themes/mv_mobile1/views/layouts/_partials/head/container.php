<?php
if(!empty($jsOption)){

}
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 12/4/2015
 * Time: 11:18 AM
 */
use yii\web\View;
$this->registerCssFile("https://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'font-OpenSans');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/font-awesome.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'font-awesome');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/simple-line-icons.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'simple-line-icons');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'bootstrap');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/swiper.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'swiper');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/jquery.mmenu.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'jquery.mmenu');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/style.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'style');


Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap.min.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.easing.min.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.mmenu.min.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->homeUrl . 'store/data/data.js', ['position'=>View::POS_BEGIN]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/swiper.min.js', ['position'=>View::POS_END]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/common.js', ['position'=>View::POS_END]);

Yii::$app->view->registerLinkTag([
    'rel'=>'shortcut icon',
    'href'=>Yii::$app->view->theme->baseUrl.'/resources/favicon/favicon.png',
    'type'=>'image/jpeg',
]);
?>
