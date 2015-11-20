<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$baseUrl = str_replace('/backend/web', '/admin', (new \yii\web\Request())->getBaseUrl());
return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
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
        'setting' => [
            'class' => 'funson86\setting\Module',
            'controllerNamespace' => 'funson86\setting\controllers'
        ],
        'news' => [
            'class' => 'vsoft\news\Module',
        ],
        'building-project' => [
            'class' => 'vsoft\buildingProject\Module',
        ],
        'ad' => [
            'class' => 'vsoft\ad\Module',
        ],
        'express' => [
            'class' => 'vsoft\express\Module',
        ],
        'cms' => [
            'class' => 'funson86\cms\Module',
            'controllerNamespace' => 'funson86\cms\controllers\backend'
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
    ],
    'params' => $params,
];
