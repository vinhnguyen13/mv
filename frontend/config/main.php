<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$baseUrl = str_replace('/frontend/web', '', (new \yii\web\Request())->getBaseUrl());
return [
    'id' => 'app-frontend',
    'name'=>'MetVuong',
//    'language'=>'vi-VN',
    'language'=>'en-US',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'languageSelector' => [
            'class' => 'frontend\components\LanguageSelector',
            'supportedLanguages' => ['en-US', 'vi-VN'],
        ],
    ],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
    ],
    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
        ],
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
        'view' => [
            'theme' => [
                'basePath' => '@webroot/themes/metvuong1',
                'baseUrl' => '/frontend/web/themes/metvuong1',
                'pathMap' => [
                    '@app/views' => '@webroot/themes/metvuong1/views',
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'jsOptions' => ['position'=>\yii\web\View::POS_HEAD]
                ],
                'yii\web\YiiAsset' => [
                    'jsOptions' => ['position'=>\yii\web\View::POS_HEAD]
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'site/login' => 'user/security/login',
                'site/signup' => 'user/registration/register',
                'abc/<action:\w+>' => 'news/<action>',

//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]
        ],
        'i18n' => [
            'translations' => [
                'user' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'ru',
                    'basePath'       => '@vendor/dektrium/yii2-user/messages',
                    'fileMap'        => [
                        'modules/user/user' => 'user.php',
                    ],
                ],
                '*' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'vi',
                    'basePath'       => '@frontend/messages',
                    'fileMap'        => [
                        'news' => 'news.php',
                    ],
                ],
            ]
        ],
    ],
    'params' => $params,
];
