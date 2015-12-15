<?php

namespace vsoft\ad\models;

use Yii;
use vsoft\ad\models\base\AdBuildingProjectCategoryBase;

/**
 * This is the model class for table "ad_building_project_category".
 *
 * @property integer $building_project_id
 * @property integer $category_id
 *
 * @property AdBuildingProject $buildingProject
 * @property AdCategory $category
 */
class AdBuildingProjectCategory extends AdBuildingProjectCategoryBase
{
	
}
