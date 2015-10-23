<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php');
require(dirname(dirname(dirname(__FILE__))) . '/vendor/yiisoft/yii2/Yii.php');
require(dirname(dirname(dirname(__FILE__))) . '/common/config/bootstrap.php');
require(dirname(dirname(__FILE__)) . '/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(dirname(dirname(dirname(__FILE__))) . '/common/config/main.php'),
    require(dirname(dirname(dirname(__FILE__))) . '/common/config/main-local.php'),
    require(dirname(dirname(__FILE__)) . '/config/main.php'),
    require(dirname(dirname(__FILE__)) . '/config/main-local.php')
);

$application = new yii\web\Application($config);
$application->run();
