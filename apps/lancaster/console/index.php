#!/usr/bin/env php
<?php
/**
 * Yii console bootstrap file.
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);

require(dirname(dirname(__FILE__)) . '/vendor/autoload.php');
require(dirname(dirname(__FILE__)) . '/vendor/yiisoft/yii2/Yii.php');
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/config/main.php'),
    require(__DIR__ . '/config/main-local.php')
);

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);