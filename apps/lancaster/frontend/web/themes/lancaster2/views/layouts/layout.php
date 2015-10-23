<?php
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;

$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/main.css", ['depends' => [\yii\bootstrap\BootstrapAsset::className()]], 'css-main');

Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/plugins/jquery.min.js', ['position'=>View::POS_HEAD]);
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/main.js', ['position'=>View::POS_HEAD]);
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
    <main>
        <?= $content ?>
    </main>
    <?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>
<?php $this->endContent();?>
