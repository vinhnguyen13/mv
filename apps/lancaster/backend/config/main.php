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
    'aliases' => [
        '@statics/web/gallery/images/'=>dirname(dirname(__DIR__)).'/store/gallery',
        '@mdm/admin' => dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/mdmsoft/yii2-admin',
        '@vsoft/user' => dirname(dirname(__DIR__)) . '/common/vendor/vsoft/user',
    ],
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
                ],
            ],
            /*'menus' => [
                'assignment' => [
                    'label' => 'Grand Access' // change label
                ],
                'route' => null, // disable menu route
            ]*/
        ],
        'express' => [
            'class' => 'vsoft\express\Module',
            'controllerNamespace' => 'vsoft\express\controllers\backend'
        ],
        'gallery' => [
            'class' => 'johnb0\gallery\Module',
            'imageUrl' => '/store/gallery'
        ],
        'translatemanager' => [
            'class' => 'lajax\translatemanager\Module',
        ],
        'cms' => [
            'class' => 'funson86\cms\Module',
            'controllerNamespace' => 'funson86\cms\controllers\backend'
        ],
        'news' => [
            'class' => 'vsoft\news\Module',
        ],
        'blog' => [
            'class' => 'funson86\blog\Module',
            'controllerNamespace' => 'funson86\blog\controllers\backend'
        ],
        'setting' => [
            'class' => 'funson86\setting\Module',
            'controllerNamespace' => 'funson86\setting\controllers'
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
        ],
        /*'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'imagine' => array(
            'class' => "ext.imagine.ImagineYii",
            //'driver' => 'gd2'
        ),
        'image' => array(
            'class' => 'mervick\image\Component',
            'driver' => 'mervick\image\drivers\GD',  //GD or Imagick
        ),
    ],
    'params' => $params,
];
