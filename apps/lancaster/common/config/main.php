<?php
return [
    'vendorPath' => dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor',
    // set target language to be Russian
    'language' => 'en-US',
    // set source language to be English
    'sourceLanguage' => 'en-US',
    'aliases' => array(
//        'webvimark' => dirname(dirname(__DIR__)) . '/vendor/webvimark'
        '@store' => dirname(dirname(__DIR__)) . '/store',
        '@vsoft/news' => dirname(__DIR__) . '/vendor/vsoft/news',
        '@vsoft/express' => dirname(__DIR__) . '/vendor/vsoft/express',
        '@vsoft/user' => dirname(__DIR__) . '/vendor/vsoft/user',
        '@funson86' => dirname(__DIR__) . '/vendor/funson86',
        '@funson86/cms' => dirname(__DIR__) . '/vendor/funson86/yii2-cms',
        '@funson86/setting' => dirname(__DIR__) . '/vendor/funson86/yii2-setting',
    ),
    'modules'=>[
        /*'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',

            // Here you can set your handler to change layout for any controller or action
            // Tip: you can use this event in any module
            'on beforeAction'=>function(yii\base\ActionEvent $event) {
                if ( $event->action->uniqueId == 'user-management/auth/login' )
                {
                    $event->action->controller->layout = 'loginLayout.php';
                };
            },
        ],*/
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
        'translatemanager' => [
			'class' => 'lajax\translatemanager\Module',
			'tmpDir' => '@frontend/runtime',
			'roles' => ['@']
        ],
    ],
    'components' => [
        'user' => [
//            'identityClass' => 'app\models\User',
            'identityClass' => 'dektrium\user\models\User',
            'enableAutoLogin' => true,
        ],
        /*'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',

            // Comment this if you don't want to record user logins
            'on afterLogin' => function($event) {
                \webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
            }
        ],*/
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOAuth',
                    'clientId' => 'google_client_id',
                    'clientSecret' => 'google_client_secret',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => 'facebook_client_id',
                    'clientSecret' => 'facebook_client_secret',
                ],
                // etc.
            ],
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
                'site/login' => 'user/security/login',
                'site/signup' => 'user/registration/register',
//                '<controller:\w+>/<id:\d+>' => '<controller>/view',
//                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
//                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',

            ]
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
            'cookieParams' => [
                /*'domain' => '.lancaster.vn',
                'httpOnly' => true,
                'path' => '/',*/
            ],
        ],
        'i18n' => [
	        'translations' => [
		        '*' => [
			        'class' => 'yii\i18n\DbMessageSource',
			        'db' => 'db',
			        'sourceMessageTable' => '{{%language_source}}',
			        'messageTable' => '{{%language_translate}}',
			        'cachingDuration' => 86400,
			        'enableCaching' => false,
		        ],
	        ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => 'username',
                'password' => 'password',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],
    ],
];
