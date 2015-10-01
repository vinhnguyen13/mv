<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'en',
    'sourceLanguage' => 'en',
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
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
            'controllerMap' => [
                'assignment' => [
                    'class' => 'mdm\admin\controllers\AssignmentController',
                    'userClassName' => 'dektrium\user\models\User', // fully qualified class name of your User model
                    // Usually you don't need to specify it explicitly, since the module will detect it automatically
                    'idField' => 'id',        // id field of your User model that corresponds to Yii::$app->user->id
                    'usernameField' => 'username', // username field of your User model
//                    'searchClass' => 'common\models\UserSearch'    // fully qualified class name of your User model for searching
                ]
            ],
            /*'menus' => [
                'assignment' => [
                    'label' => 'Grand Access' // change label
                ],
                'route' => null, // disable menu route
            ]*/
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'request' => [
            'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
            'cookieValidationKey' => 'xyctuyvibonp',
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
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                'site/login' => 'user/security/login',
                'site/signup' => 'user/registration/register',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]
        ],
        'setting' => [
            'class' => 'funson86\setting\Setting',
        ],
    ],
];
