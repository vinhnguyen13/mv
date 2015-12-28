<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdProductSavedBase;

/**
 * This is the model class for table "ad_product_saved".
 *
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $saved_at
 *
 * @property AdProduct $product
 * @property User $user
 */
class AdProductSaved extends AdProductSavedBase
{

}
