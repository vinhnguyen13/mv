<?php

namespace vsoft\ad\models;

use vsoft\ad\models\base\AdContractorBuildingProjectBase;
use Yii;

/**
 * This is the model class for table "ad_contractor_building_project".
 *
 * @property integer $building_project_id
 * @property integer $contractor_id
 *
 * @property AdBuildingProject $buildingProject
 * @property AdContractor $contractor
 */
class AdContractorBuildingProject extends AdContractorBuildingProjectBase
{

}
