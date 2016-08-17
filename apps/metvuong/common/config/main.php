<?php
return [
    'vendorPath' => dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor',
    'language' => 'en',
    'sourceLanguage' => 'en',
    'aliases' => array(
        '@store' => dirname(dirname(__DIR__)) . '/store',
        '@vsoft/news' => dirname(__DIR__) . '/myvendor/vsoft/news',
        '@vsoft/coupon' => dirname(__DIR__) . '/myvendor/vsoft/coupon',
        '@vsoft/ec' => dirname(__DIR__) . '/myvendor/vsoft/ec',
        '@vsoft/buildingProject' => dirname(__DIR__) . '/myvendor/vsoft/building-project',
        '@vsoft/express' => dirname(__DIR__) . '/myvendor/vsoft/express',
        '@vsoft/user' => dirname(__DIR__) . '/myvendor/vsoft/user',
        '@vsoft/tracking' => dirname(__DIR__) . '/myvendor/vsoft/tracking',
        '@vsoft/chat' => dirname(__DIR__) . '/myvendor/vsoft/chat',
        '@vsoft/ad' => dirname(__DIR__) . '/myvendor/vsoft/ad',
        '@vsoft/craw' => dirname(__DIR__) . '/myvendor/vsoft/craw',
        '@funson86' => dirname(__DIR__) . '/myvendor/funson86',
        '@funson86/cms' => dirname(__DIR__) . '/myvendor/funson86/yii2-cms',
        '@funson86/setting' => dirname(__DIR__) . '/myvendor/funson86/yii2-setting',
        '@linslin/yii2/curl' => dirname(__DIR__) . '/myvendor/linslin/yii2-curl',
        '@dkeeper/mobiledetect' => dirname(__DIR__) . '/myvendor/dkeeper/yii2-mobiledetect-master',
    ),
    'modules' => [
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
            'cache' => 'cache'
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
