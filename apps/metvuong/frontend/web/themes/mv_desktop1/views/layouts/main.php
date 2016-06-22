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
    <meta name='viewport' content='width=device-width, initial-scale=1.0' >
    <?= Html::csrfMetaTags() ?>
    <title><?= (!empty($this->title) ? Html::encode($this->title).', ' : '').Yii::$app->name ?></title>
    <?php $this->head() ?>
</head>
<body <?=!empty($this->params['body']) ? \common\components\Util::me()->arrayToHtmlAttributes($this->params['body']) : ''?>>
    <?php $this->beginBody() ?>
        <?= $content ?>
    <?php $this->endBody() ?>
    <?php $this->beginContent('@app/views/chat/_partials/connect.php'); ?><?php $this->endContent();?>
    <?php $this->beginContent('@app/views/layouts/_partials/analyticstracking.php'); ?><?php $this->endContent();?>

    <div id="alert-noti"></div>

    <input type="hidden" value="0" id="checkUserFirst" />
    
</body>
</html>
<?php $this->endPage() ?>