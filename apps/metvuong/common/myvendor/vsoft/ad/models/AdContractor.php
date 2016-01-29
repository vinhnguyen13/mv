<?php

namespace vsoft\ad\models;

use vsoft\ad\models\base\AdContractorBase;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "ad_contractor".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $fax
 * @property string $website
 * @property string $email
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 *
 * @property AdContractorBuildingProject[] $adContractorBuildingProjects
 */
class AdContractor extends AdContractorBase
{

}
