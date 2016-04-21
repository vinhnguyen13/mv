<?php

use yii\db\Schema;
use yii\db\Migration;

class m160421_102627_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_building_project`   
						  ADD COLUMN `home_no` VARCHAR(32) NULL AFTER `click`,
						  ADD COLUMN `street_id` INT NULL AFTER `home_no`,
						  ADD COLUMN `ward_id` INT NULL AFTER `street_id`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_building_project`   
						  DROP COLUMN `home_no`, 
						  DROP COLUMN `street_id`, 
						  DROP COLUMN `ward_id`;");
    }
}
