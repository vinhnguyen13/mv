<?php

namespace vsoft\ad\models;

use Yii;
use common\models\AdContractorBuildingProject as ACBP;

/**
 * This is the model class for table "ad_contractor_building_project".
 *
 * @property integer $building_project_id
 * @property integer $contractor_id
 *
 * @property AdBuildingProject $buildingProject
 * @property AdContractor $contractor
 */
class AdContractorBuildingProject extends ACBP
{

}
