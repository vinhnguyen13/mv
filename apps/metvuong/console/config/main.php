<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => array(
        '@dektrium/user' => dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/dektrium/yii2-user',
        '@vendor' => dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor',
        '@common' => dirname(dirname(__DIR__)) . '/common',
        '@keltstr/simplehtmldom' => dirname(dirname(__DIR__)) . '/common/myvendor/keltstr/simplehtmldom',
        '@linslin/yii2/curl' => dirname(dirname(__DIR__)) . '/common/myvendor/linslin/yii2-curl',
        '@vsoft' => dirname(dirname(__DIR__)) . '/common/myvendor/vsoft',
    ),
    'controllerMap' => [
        'crawler' => [
            'class' => 'console\controllers\CrawlerController'
        ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
