<?php

namespace vsoft\ad\models;

use Yii;
use yii\helpers\ArrayHelper;

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
    public static function getReportName($id=null){
        $report_list = Yii::$app->db->cache(function(){
            return \vsoft\ad\models\ReportType::find()->where(['is_user' => \vsoft\ad\models\ReportType::report_product])->all();
        });

        $data = ArrayHelper::map($report_list, 'id', 'name');

        if ($id !== null && isset($data[$id])) {
            return $data[$id];
        } else {
            return $data;
        }
    }
}
