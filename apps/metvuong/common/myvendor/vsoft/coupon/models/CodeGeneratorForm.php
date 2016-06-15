<?php
/**
 * Created by PhpStorm.
 * User: Nhut Tran
 * Date: 6/1/2016 11:51 AM
 */

namespace vsoft\coupon\models;


use yii\base\Model;

class CodeGeneratorForm extends Model
{
    public $cp_event_id;
    public $no_of_coupons;
    public $length;
    public $prefix;
    public $suffix;
    public $numbers;
    public $letters;
    public $symbols;
    public $random_register;
    public $mask;
}