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
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'bootstrap');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/swiper.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'swiper');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/jquery.mmenu.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'jquery.mmenu');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/rippler.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'rippler');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/style.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'style');


Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/velocity.js', ['position'=>View::POS_HEAD]);
//Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.easing.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/string-helper.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.mmenu.min.js', ['position'=>View::POS_HEAD]);
// Yii::$app->getView()->registerJsFile(Yii::$app->homeUrl . 'store/data/data.js', ['position'=>View::POS_BEGIN]);
//Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/swiper.jquery.min.js', ['position'=>View::POS_HEAD]); /* swiper js o tren head de slide ben duoi goi thu vien thuc thi */
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.rippler.min.js', ['position'=>View::POS_HEAD]);
//Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.rateit.js', ['position'=>View::POS_HEAD]);
//Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/clipboard.min.js', ['position'=>View::POS_HEAD]);
//Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.slimscroll.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/common.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.slimscroll.min.js', ['position'=>View::POS_END]);

Yii::$app->view->registerLinkTag([
    'rel'=>'shortcut icon',
    'href'=>Yii::$app->view->theme->baseUrl.'/resources/favicon/favicon.png',
    'type'=>'image/jpeg',
]);
?>
