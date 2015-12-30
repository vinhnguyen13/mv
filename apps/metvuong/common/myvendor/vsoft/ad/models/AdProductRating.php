<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdProductRatingBase;

/**
 * This is the model class for table "ad_product_rating".
 *
 * @property integer $user_id
 * @property integer $product_id
 * @property string $core
 * @property integer $rating_at
 *
 * @property AdProduct $product
 * @property User $user
 */
class AdProductRating extends AdProductRatingBase
{
    public $avgCore;

}
