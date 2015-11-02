<?php
use yii\web\View;
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/bootstrap.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'bootstrap');
$this->registerCssFile("https://fonts.googleapis.com/css?family=Roboto:400,300,700", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'font-roboto');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/font-awesome.min.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'font-awesome');
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/style-custom.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()],], 'style-custom');

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/jquery.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/bootstrap.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/menu.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/common.js', ['position'=>View::POS_HEAD]);

Yii::$app->view->registerLinkTag([
    'rel'=>'shortcut icon',
    'href'=>Yii::$app->view->theme->baseUrl.'/resources/favicon/favicon.png',
    'type'=>'image/jpeg',
]);
?>


<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <?php $this->beginContent('@app/views/layouts/_partials/news/nav.php'); ?><?php $this->endContent();?>
    <div class="o-wrapper clearfix">
        <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
        <div class="container cd-main-content">
            <?= $content ?>
        </div>
        <?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>
    </div>
<?php $this->endContent();?>

