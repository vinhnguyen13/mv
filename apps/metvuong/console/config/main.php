<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => array(
        '@store' => dirname(dirname(__DIR__)) . '/store',
        '@dektrium/user' => dirname(dirname(dirname(dirname(__DIR__)))) . '/myvendor/dektrium/yii2-user',
        '@vendor' => dirname(dirname(dirname(dirname(__DIR__)))) . '/myvendor',
        '@common' => dirname(dirname(__DIR__)) . '/common',
        '@keltstr/simplehtmldom' => dirname(dirname(__DIR__)) . '/common/myvendor/keltstr/simplehtmldom',
        '@linslin/yii2/curl' => dirname(dirname(__DIR__)) . '/common/myvendor/linslin/yii2-curl',
        '@funson86' => dirname(dirname(__DIR__)) . '/common/myvendor/funson86',
        '@funson86/cms' => dirname(__DIR__) . '/myvendor/funson86/yii2-cms',
        '@funson86/setting' => dirname(__DIR__) . '/myvendor/funson86/yii2-setting',
        '@vsoft' => dirname(dirname(__DIR__)) . '/common/myvendor/vsoft',
    ),
    'controllerMap' => [
        'crawler' => [
            'class' => 'console\controllers\CrawlerController'
        ],
    ],
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'baseUrl' => 'http://metvuong.com/',
            'scriptUrl' => 'http://metvuong.com/',
            'hostInfo' => 'http://metvuong.com/',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '/' => 'site/index',
                'page/<view>' => 'site/page',

                'tin-tuc' => 'news/index',
                'tin-tuc/<cat_id:\d+>-<cat_slug>' => 'news/list',
                'tin-tuc/chi-tiet/<id:\d+>-<slug>' => 'news/view',
                'du-an' => 'building-project/index',
                'du-an/<slug>' => 'building-project/view',
                'can-mua-<type:1>-<city_id>-<district_id>' => 'ad/index',
                'can-thue-<type:2>-<city_id>-<district_id>' => 'ad/index',
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

            ]
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'setting' => [
            'class' => 'funson86\setting\Setting',
        ],
    ],
    'params' => $params,
];
