<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
use \yii\web\Request;
$baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());
return [
    'id' => 'app-frontend',
    'name'=>'Lancaster',
    'language'=>'vi-VN',
    'basePath' => dirname(__DIR__),
    'aliases' => array(
        '@vsoft' => dirname(dirname(__DIR__)) . '/common/vendor/vsoft'
    ),
    'bootstrap' => [
        'log',
        'languageSelector' => [
            'class' => 'frontend\components\LanguageSelector',
            'supportedLanguages' => ['en-US', 'vi-VN'],
        ],
    ],
    'language'=>'vi-VN',
    'controllerNamespace' => 'frontend\controllers',
    'modules'=>[
        'express' => [
            'class' => 'vsoft\express\Module',
            'controllerNamespace' => 'vsoft\express\controllers\frontend'
        ],
        'cms' => [
            'class' => 'funson86\cms\Module',
            'controllerNamespace' => 'funson86\cms\controllers\frontend'
        ],
        'gallery' => [
            'class' => 'johnb0\gallery\Module',
            'imageUrl' => '/store/gallery'
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'contact' => 'express/contact/index',
                'booking' => 'express/booking/index',
                'booking/booking-hotel' => 'express/booking/booking-hotel',
                'about' => 'express/about/index',
                'about/view/<id:\d+>-<slug>' => 'express/about/detail',
                'news' => 'express/news/index',
                'news/view/<id:\d+>-<slug>' => 'express/news/detail',
                'pages/<view>' => 'site/page',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]
        ],
        'meta' =>[
            'class' => 'frontend\components\MetaExt',
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
                'basePath' => '@webroot/themes/lancaster2',
                'baseUrl' => '/frontend/web/themes/lancaster2',
                'pathMap' => [
                    '@app/views' => '@webroot/themes/lancaster2/views',
                    '@vsoft/express/views' => '@webroot/themes/lancaster2/views/express',
                ],
            ],
        ],
        'setting' => [
            'class' => 'funson86\setting\Setting',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'jsOptions' => ['position'=>\yii\web\View::POS_HEAD],
                    'js' => [
//                        '//code.jquery.com/jquery-1.10.2.min.js',
                    ]
                ],
                'yii\web\YiiAsset' => [
                    'jsOptions' => ['position'=>\yii\web\View::POS_HEAD]
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'xx-XX', // ???? ????????????
                    'sourceMessageTable' => 'language_source',
                    'messageTable' => 'language_translate',
                    'cachingDuration' => 86400,
                    'enableCaching' => true,
                ],
                'user' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'ru',
                    'basePath'       => '@vendor/dektrium/yii2-user/messages',
                    'fileMap'        => [
                        'modules/user/user' => 'user.php',
                    ],
                ],
                'express*' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'forceTranslation' => true,
                    'basePath'       => '@vendor/vsoft/express/messages',
                    'fileMap'        => [
                        'express' => 'express.php',
                        'express/about' => 'about.php',
                        'express/booking' => 'booking.php',
                        'express/contact' => 'contact.php',
                        'express/news' => 'news.php',
                    ],
                ],
            ]
        ],
    ],
    'params' => $params,
];
