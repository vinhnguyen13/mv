<?php
$this->registerCssFile(Yii::$app->view->theme->baseUrl."/resources/css/screen.css", [
    'depends' => [\yii\bootstrap\BootstrapAsset::className()],
], 'css-screen');
?>
<?php $this->beginContent('@app/views/layouts/main.php'); ?>
    <?php $this->beginContent('@app/views/layouts/_partials/header.php'); ?><?php $this->endContent();?>
    <div class="layout">
        <?= $content ?>
    </div>
    <?php $this->beginContent('@app/views/layouts/_partials/footer.php'); ?><?php $this->endContent();?>
<?php $this->endContent();?>