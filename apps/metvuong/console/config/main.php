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
        '@keltstr/simplehtmldom' => dirname(dirname(__DIR__)) . '/common/vendor/keltstr/simplehtmldom',
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
