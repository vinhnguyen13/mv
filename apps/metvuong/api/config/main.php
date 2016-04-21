<?php

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$baseUrl = str_replace('/api/web', '/api', (new \yii\web\Request())->getBaseUrl());
return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'aliases' => array(
//        '@filsh/yii2/oauth2server' => dirname(dirname(__DIR__)) . '/common/myvendor/filsh/yii2-oauth2-server',
//        '@OAuth2' => dirname(dirname(__DIR__)) . '/common/myvendor/bshaffer/oauth2-server-php/src/OAuth2',
    ),
    'modules' => [
        'oauth2' => [
            'class' => 'api\modules\v1\Module',
            /*'options' => [
                'token_param_name' => 'accessToken',
                'access_lifetime' => 3600,
                'id_lifetime' => 3600,
                'refresh_token_lifetime' => 3600,
            ],*/
            'storageMap' => [
                'user_credentials' => 'api\modules\v1\models\Member',
            ],
            'grantTypes' => [
                'client_credentials' => [
                    'class' => 'OAuth2\GrantType\ClientCredentials',
                    'allow_public_clients' => false
                ],
                'user_credentials' => [
                    'class' => 'OAuth2\GrantType\UserCredentials',
                ],
                'refresh_token' => [
                    'class' => 'OAuth2\GrantType\RefreshToken',
                    'always_issue_new_refresh_token' => true
                ],
                'authorization_code' => [
                    'class' => 'OAuth2\GrantType\AuthorizationCode',
                ],
            ]
        ],
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'request' => [
            'baseUrl' => $baseUrl,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/migration', 'v1/member'],
                    'tokens' => [
                        '{id}' => '<id:\\w+>'
                    ]
                    
                ],
                'POST v1/test/<action:\w+>' => 'v1/test/<action>',
            ],
        ]
    ],
    'params' => $params,
];



