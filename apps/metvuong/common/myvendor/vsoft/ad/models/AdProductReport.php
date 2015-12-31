<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdProductReportBase;

/**
 * This is the model class for table "ad_product_report".
 *
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $type
 * @property string $description
 * @property string $ip
 * @property integer $status
 * @property integer $report_at
 *
 * @property AdProduct $product
 * @property User $user
 */
class AdProductReport extends AdProductReportBase
{

}
