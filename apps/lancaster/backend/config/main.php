<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$baseUrl = str_replace('/backend/web', '/admin', (new \yii\web\Request())->getBaseUrl());
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'aliases' => [
        '@statics/web/gallery/images/'=>dirname(dirname(__DIR__)).'/store/gallery'
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'],
            'generators' => [
                'myCrud' => [
                    'class' => 'yii\gii\generators\crud\Generator',
                    'templates' => [
                        'my' => '@app/myTemplates/crud/default',
                    ]
                ]
            ],
        ],
        'express' => [
            'class' => 'vsoft\express\Module',
            'controllerNamespace' => 'vsoft\express\controllers\backend'
        ],
        'gallery' => [
            'class' => 'johnb0\gallery\Module',
            'imageUrl' => '/store/gallery'
        ],
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
        ],
        'cms' => [
            'class' => 'funson86\cms\Module',
            'controllerNamespace' => 'funson86\cms\controllers\backend'
        ],
        'blog' => [
            'class' => 'funson86\blog\Module',
            'controllerNamespace' => 'funson86\blog\controllers\backend'
        ],
        'setting' => [
            'class' => 'funson86\setting\Module',
            'controllerNamespace' => 'funson86\setting\controllers'
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
        ],
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],*/
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'imagine' => array(
            'class' => "ext.imagine.ImagineYii",
            //'driver' => 'gd2'
        ),
        'image' => array(
            'class' => 'mervick\image\Component',
            'driver' => 'mervick\image\drivers\GD',  //GD or Imagick
        ),
    ],
    'params' => $params,
];
