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
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableConfirmation' => true,
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['superadmin'],
            'modelMap' => [
                'User' => 'dektrium\user\models\User',
                'Profile' => 'dektrium\user\models\Profile',
                'Account' => 'dektrium\user\models\Account',
            ],
            'controllerMap' => [
                'admin' => 'vsoft\user\controllers\AdminController',
            ],
        ],
        'express' => [
            'class' => 'vsoft\express\Module',
        ],
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
            'tmpDir' => '@frontend/runtime',
            'roles' => ['@']
        ],
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
            'identityClass' => 'vsoft\user\models\User',
            'enableAutoLogin' => true,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
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
