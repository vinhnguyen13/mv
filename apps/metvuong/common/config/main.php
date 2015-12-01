<?php
return [
    'vendorPath' => dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor',
    'language' => 'en',
    'sourceLanguage' => 'en',
    'aliases' => array(
        '@store' => dirname(dirname(__DIR__)) . '/store',
        '@vsoft/news' => dirname(__DIR__) . '/vendor/vsoft/news',
        '@vsoft/buildingProject' => dirname(__DIR__) . '/vendor/vsoft/building-project',
        '@vsoft/express' => dirname(__DIR__) . '/vendor/vsoft/express',
        '@vsoft/user' => dirname(__DIR__) . '/vendor/vsoft/user',
        '@vsoft/ad' => dirname(__DIR__) . '/vendor/vsoft/ad',
        '@funson86' => dirname(__DIR__) . '/vendor/funson86',
        '@funson86/cms' => dirname(__DIR__) . '/vendor/funson86/yii2-cms',
        '@funson86/setting' => dirname(__DIR__) . '/vendor/funson86/yii2-setting',
    ),
    'modules' => [

    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => '6zRXJTEnacve8RrgN6K5eoXf0JI0AwFs',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            // 'db' => 'mydb',
            'sessionTable' => 'session',
        ],
        'user' => [
            'identityClass' => 'dektrium\user\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
//                'site/login' => 'user/security/login',
//                'site/signup' => 'user/registration/register',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]
        ],
        'setting' => [
            'class' => 'funson86\setting\Setting',
        ],
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD',  //GD or Imagick
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    'db' => 'db',
                    'sourceLanguage' => 'en-US',
                    'sourceMessageTable' => '{{%language_source}}',
                    'messageTable' => '{{%language_translate}}',
                    'cachingDuration' => 86400,
                    'enableCaching' => false,
                ],
            ],
        ],
    ],
];
