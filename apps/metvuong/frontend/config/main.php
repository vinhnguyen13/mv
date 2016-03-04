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
    'language'=>'vi-VN',
//    'language'=>'en-US',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'MVBootstrap' => [
            'class' => 'frontend\components\MVBootstrap',
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
                'User' => 'frontend\models\User',
                'Account' => 'frontend\models\Account',
                'Profile' => 'frontend\models\Profile',
            ],
            'controllerMap' => [
                'security' => 'frontend\controllers\SecurityController',
                'registration' => 'frontend\controllers\RegistrationController',
            ],
            'urlRules' => [
                '<id:\d+>'                               => 'profile/show',
                '<action:(login|logout)>'                => 'security/<action>',
                '<action:(register|resend)>'             => 'registration/<action>',
                'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
                'forgot'                                 => 'recovery/request',
                'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
                'settings/<action:\w+>'                  => 'settings/<action>'
            ]
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'on afterLogin' => ['frontend\components\Login', 'handleAfterLogin'],
            'loginUrl' => ['member/login'],
        ],
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
                'basePath' => '@webroot/themes/metvuong2',
                'baseUrl' => '/frontend/web/themes/metvuong2',
                'pathMap' => [
                    '@app/views' => '@webroot/themes/metvuong2/views',
                    '@dektrium/user/views' => '@webroot/themes/metvuong2/views',
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/' => 'site/index',
//                'site/signup' => 'user/registration/register',
                'news' => 'news/index',
                'news/<cat_id:\d+>-<cat_slug>' => 'news/list',
//                'news/<cat_id:\d+>-<cat_slug>/<id:\d+>-<slug>' => 'news/view',
                'building' => 'building-project/index',
                'building/<slug>' => 'building-project/view',
                'real-estate/result' => 'ad/index',
                'real-estate/redirect' => 'ad/redirect',
                'real-estate/post' => 'ad/post',
                'real-estate/post-listing' => 'ad/post-listing',
                'real-estate/detail/<id:\d+>-<slug>' => 'ad/detail',
                'member/<usrn>/avatar' => 'member/avatar',
                'chat/with/<username>' => 'chat/with',
                '<username>' => 'member/profile',
                '<username>/notification' => 'dashboard/notification',
                '<username>/ad' => 'dashboard/ad',
                '<username>/chat' => 'chat/index',
                'pricing/package' => 'payment/package',
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
                'user*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US',
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => false,
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
        'mobileDetect' => [
//            'class' => dirname(dirname(__DIR__)) . '/common/myvendor/dkeeper/yii2-mobiledetect-master/Detect'
            'class' => 'dkeeper\mobiledetect\Detect'
        ]
    ],
    'params' => $params,
];
