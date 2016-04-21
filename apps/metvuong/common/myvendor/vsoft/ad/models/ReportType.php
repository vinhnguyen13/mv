<?php

namespace vsoft\ad\models;

use Yii;

/**
 * This is the model class for table "report_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $is_user
 * @property integer $created_at
 * @property integer $created_by
 */
class ReportType extends \vsoft\ad\models\base\ReportType
{
    const report_user = 1;
    const report_product = 0;

//    public static function getType()
//    {
//        return [ReportType::report_product, ReportType::report_user];
//    }
}
