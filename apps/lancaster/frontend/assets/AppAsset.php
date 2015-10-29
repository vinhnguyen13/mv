<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/site.css',
    ];
    public $js = [
        '/frontend/web/themes/lancaster2/resources/js/plugins/select2.min.js',
        '/frontend/web/themes/lancaster2/resources/js/plugins/jquery-ui.min.js',
        '/frontend/web/themes/lancaster2/resources/js/book.js',
    ];
    public $depends = [
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];

//Yii::$app->getView()->registerCssFile($path . '/resources/css/select2.css');
//Yii::$app->getView()->registerCssFile($path . '/resources/css/jquery-ui.css');
//
//Yii::$app->getView()->registerJsFile($path . '/resources/js/plugins/select2.min.js');
//Yii::$app->getView()->registerJsFile($path . '/resources/js/plugins/jquery-ui.min.js');
//Yii::$app->getView()->registerJsFile($path . '/resources/js/book.js');
}
