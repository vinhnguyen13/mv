<?php
use yii\web\View;
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/screen.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
], 'css-screen');
Yii::$app->getView()->registerJsFile(Yii::$app->view->theme->baseUrl.'/resources/js/screen.js', ['position'=>View::POS_END]);
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
    <div class="layout">
        <?php $this->beginContent('@app/views/news/index.php'); ?><?php $this->endContent();?>
    </div>
    <?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>
<?php $this->endContent();?>