<?php

namespace vsoft\craw\models;

use vsoft\craw\models\base\AdInvestorBuildingProjectBase;
use Yii;

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
