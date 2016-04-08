<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Yii::$app->name . (!empty($this->title) ? ' - '.Html::encode($this->title) : '') ?></title>
    <?php $this->head() ?>
</head>
<body <?=!empty($this->params['body']) ? \common\components\Util::me()->arrayToHtmlAttributes($this->params['body']) : ''?>>
    <?php $this->beginContent('@app/views/layouts/_partials/icons.php'); ?><?php $this->endContent();?>
    <?php $this->beginBody() ?>
        <?= $content ?>
    <?php $this->endBody() ?>
    <?php $this->beginContent('@app/views/chat/_partials/connect.php'); ?><?php $this->endContent();?>
    <?php $this->beginContent('@app/views/layouts/_partials/analyticstracking.php'); ?><?php $this->endContent();?>
    <div class="box-chat-footer">
        <a href="#" class="close-box">
            <span class="icon-mv"><span class="icon-close-icon"></span></span>
        </a>
        
    </div>
</body>
</html>
<?php $this->endPage() ?>