<?php

namespace vsoft\ad\models;

use vsoft\ad\models\base\AdArchitectBase;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "ad_architect".
 *
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $website
 * @property string $email
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property AdArchitectBuildingProject[] $adArchitectBuildingProjects
 */
class AdArchitect extends AdArchitectBase
{

}
