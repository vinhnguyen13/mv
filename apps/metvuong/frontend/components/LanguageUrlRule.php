<?php
/**
 * Created by PhpStorm.
 * User: vinhnguyen
 * Date: 11/3/2015
 * Time: 11:25 AM
 */
namespace frontend\components;
use Yii;
use yii\web\UrlRule;

class LanguageUrlRule extends UrlRule
{
    public function init()
    {
        if ($this->pattern !== null) {
            $this->pattern = '<language>/' . $this->pattern;
            // for subdomain it should be:
            // $this->pattern =  'http://<language>.example.com/' . $this->pattern,
        }
        $this->defaults['language'] = Yii::$app->language;
        parent::init();
    }
}