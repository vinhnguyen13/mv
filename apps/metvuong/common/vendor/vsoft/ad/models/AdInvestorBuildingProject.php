<?php

namespace common\vendor\vsoft\ad\models;

use Yii;
use common\vendor\vsoft\ad\models\base\AdInvestorBuildingProjectBase;

/**
 * This is the model class for table "ad_investor_building_project".
 *
 * @property integer $building_project_id
 * @property integer $investor_id
 *
 * @property AdBuildingProject $buildingProject
 * @property AdInvestor $investor
 */
class AdInvestorBuildingProject extends AdInvestorBuildingProjectBase
{
	
}
