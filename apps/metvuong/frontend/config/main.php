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
//    'defaultRoute' => 'news/index',
    'modules' => [
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableConfirmation' => false,
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['superadmin'],
            'modelMap' => [
                'User' => 'dektrium\user\models\User',
                'Account' => 'dektrium\user\models\Account',
                'RegistrationForm' => 'dektrium\user\models\RegistrationForm',
                'LoginForm' => 'dektrium\user\models\LoginForm',
            ],
            'controllerMap' => [
                'admin' => 'vsoft\user\controllers\AdminController',
                'security' => 'vsoft\user\controllers\SecurityController',
                'registration' => 'vsoft\user\controllers\RegistrationController',
            ],
        ],
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
                    '@dektrium/user/views' => '@webroot/themes/metvuong1/views',
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
//                'site/login' => 'user/security/login',
//                'site/signup' => 'user/registration/register',
                '<cat_id:\d+>-<cat_slug>/<id:\d+>-<slug>' => 'news/view',
                '<cat_id:\d+>-<slug>' => 'news/list',
                'building/<slug>' => 'building-project/view',
                'real-estate/result' => 'ads/index',
                'real-estate/search' => 'ads/search',
                'real-estate/post' => 'ads/post',
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
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ],
            'languages' => ['en-US'=>'en-US', 'vi-VN'=>'vi-VN'],
            'enableDefaultLanguageUrlCode'=>true,
            'ignoreLanguageUrlPatterns'=>[
                '#^site/language#' => '#^site/language#',
                '#^express/upload/image#' => '#^express/upload/image#',
                '#^express/upload/editor-image#' => '#^express/upload/editor-image#',
                '#^store/news/show#' => '#^store/news/show#',
            ],
//            'ruleConfig' => ['class' => frontend\components\LanguageUrlRule::className()]
        ],*/
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
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US',
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => false,
                ],
            ]
        ],
        'meta' =>[
            'class' => 'frontend\components\MetaExt',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [],
                ],
                'yii\web\JqueryAsset' => [
                    'js'=>[],
                    'jsOptions' => ['position'=>\yii\web\View::POS_HEAD]
                ],
            ],
        ],
    ],
    'params' => $params,
];
