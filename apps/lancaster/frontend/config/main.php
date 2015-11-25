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
        ],
        'building' => [
            'class' => 'vsoft\building\Module',
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
                'site/login' => 'user/security/login',
                'site/signup' => 'user/registration/register',
//                'news/<action:\w+>' => 'news/<action>',
                '<cat>/<id:\d+>-<slug>' => 'site/news-detail',
                'news' => 'site/news',
                'about' => 'site/about-us',
                'contact' => 'contact/index',
                'booking' => 'booking/index',
                'building/<lancaster>' => 'site/index',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]
        ],
        /*'urlManager' => [
//            'class' => 'yii\web\UrlManager',
            'class' => 'frontend\components\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'site/login' => 'user/security/login',
                'site/signup' => 'user/registration/register',
//                'news/<action:\w+>' => 'news/<action>',
                '<cat_id:\d+>-<cat_slug>/<id:\d+>-<slug>' => 'news/view',
                '<cat_id:\d+>-<slug>' => 'news/list',
                'contact' => 'contact',
                'booking' => 'booking',
				'<lancaster>' => 'site/index',
                
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ],
            'languages' => ['en-US'=>'en-US', 'vi-VN'=>'vi-VN'],
            'enableDefaultLanguageUrlCode'=>true,
            'ignoreLanguageUrlPatterns'=>[
                '#^site/language#' => '#^site/language#',
                '#^express/upload/image#' => '#^express/upload/image#',
                '#^tool/index#' => '#^tool/index#',
                '#^tool/get-chart#' => '#^tool/get-chart#',
                '#^tool/save-step#' => '#^tool/save-step#',
            ],
//            'ruleConfig' => ['class' => frontend\components\LanguageUrlRule::className()]
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
        'meta' =>[
            'class' => 'frontend\components\MetaExt',
        ],
    ],
    'params' => $params,
];
