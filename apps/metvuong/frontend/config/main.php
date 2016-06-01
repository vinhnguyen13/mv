<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$baseUrl = str_replace('/frontend/web', '', (new \yii\web\Request())->getBaseUrl());
$return =  [
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
//                '<id:\d+>'                               => 'profile/show',
//                '<action:(login|logout)>'                => 'security/<action>',
//                '<action:(register|resend)>'             => 'registration/<action>',
//                'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
//                'forgot'                                 => 'recovery/request',
//                'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
//                'settings/<action:\w+>'                  => 'settings/<action>'
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
                'basePath' => '@webroot/themes/mv_desktop1',
                'baseUrl' => '/frontend/web/themes/mv_desktop1',
                'pathMap' => [
                    '@app/views' => '@webroot/themes/mv_desktop1/views',
                    '@dektrium/user/views' => '@webroot/themes/mv_desktop1/views',
                ],
            ],
        ],
        /*'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/' => 'site/index',
                'news' => 'news/index',
                'news/<cat_id:\d+>-<cat_slug>' => 'news/list',
                'news/view/<id:\d+>-<slug>' => 'news/view',
                'du-an' => 'building-project/index',
                'du-an/<slug>' => 'building-project/view',
                'real-estate/result' => 'ad/index',
                'real-estate/redirect' => 'ad/redirect',
                'real-estate/post' => 'ad/post',
                'real-estate/post-listing' => 'ad/post-listing',
                'real-estate/detail/<id:\d+>-<slug>' => 'ad/detail',
                'real-estate/update/<id:\d+>' => 'ad/update',
                'member/<usrn>/avatar' => 'member/avatar',
                'chat/with/<username>' => 'chat/with',
                '<username>' => 'member/profile',
                '<username>/update' => 'member/update-profile',
                '<username>/notification' => 'notification/index',
                '<username>/notification/update' => 'notification/update',
                '<username>/ad' => 'dashboard/ad',
                '<username>/chat' => 'chat/index',
                'pricing/package' => 'payment/package',

                'mvuser/protect/<action>' => 'user/security/<action>',
                'mvuser/join/<action>' => 'user/registration/<action>',
                'mvuser/forgot/<action>' => 'user/recovery/<action>',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]
        ],*/
        'urlManager' => [
            'class' => 'frontend\components\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [

            ],
            'languages' => ['en-US'=>'en-US', 'vi-VN'=>'vi-VN'],
            'enableDefaultLanguageUrlCode'=>true,
            'enableLocaleUrls'=>true,
            'ignoreLanguageUrlPatterns'=>[
                '#^site/language#' => '#^site/language#',
                '#^express/upload/image#' => '#^express/upload/image#',
                '#^express/upload/editor-image#' => '#^express/upload/editor-image#',
                '#^store/news/show#' => '#^store/news/show#',
                '#^user/security/*#' => '#^user/security/*#',
                '#^mvuser/protect/*#' => '#^mvuser/protect/*#',
//                '#^listing/detail#' => '#^listing/detail#',
                '#^listing/get-area#' => '#^listing/get-area#',
            ],
//            'ruleConfig' => ['class' => frontend\components\LanguageUrlRule::className()]
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en', /** with this language, is not translate **/
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => true,
                ],
                'user*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en', /** with this language, is not translate **/
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => true,
                ],
            ]
        ],
        'meta' =>[
            'class' => 'frontend\components\MetaExt',
        ],
        'assetManager' => [
            /*
             * add timestamp after file
             */
            'appendTimestamp' => true,
        ],
        /*'assetManager' => [
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
        ],*/
        'mobileDetect' => [
//            'class' => dirname(dirname(__DIR__)) . '/common/myvendor/dkeeper/yii2-mobiledetect-master/Detect'
            'class' => 'dkeeper\mobiledetect\Detect'
        ]
    ],
    'params' => $params,
];

//$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//$segments = explode('/', $path);

$return['components']['urlManager']['rules'] = [
    '/' => 'site/index',
    'page/<view>' => 'site/page',

    'tin-tuc' => 'news/index',
    'tin-tuc/<cat_id:\d+>-<cat_slug>' => 'news/list',
    'tin-tuc/chi-tiet/<id:\d+>-<slug>' => 'news/view',
    'du-an' => 'building-project/index',
    'du-an/<slug>' => 'building-project/view',
    '<urlSeg>' => 'ad/index',
    'real-estate/redirect' => 'ad/redirect',
    'dang-tin' => 'ad/post',
    'real-estate/post-listing' => 'ad/post-listing',
    'real-estate/detail/<id:\d+>-<slug>' => 'ad/detail',
    'real-estate/update/<id:\d+>' => 'ad/update',
    'member/<usrn>/avatar' => 'member/avatar',
    'chat/with/<username>' => 'chat/with',
    'goi-gia' => 'payment/package',

    '<username>' => 'member/profile',
    '<username>/cap-nhat' => 'member/update-profile',
    '<username>/thong-bao' => 'notification/index',
    '<username>/thong-bao/cap-nhat' => 'notification/update',
    '<username>/danh-sach-tin-dang' => 'dashboard/ad',
    '<username>/tro-chuyen' => 'chat/index',

    'mvuser/protect/<action>' => 'user/security/<action>',
    'mvuser/join/<action>' => 'user/registration/<action>',
    'mvuser/forgot/<action>' => 'user/recovery/<action>',

    'listing/<action>' => 'ad/<action>',



];

return $return;

