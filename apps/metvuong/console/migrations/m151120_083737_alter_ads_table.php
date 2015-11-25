<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_083737_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_building_project`   
		  ADD COLUMN `logo` VARCHAR(32) NULL AFTER `name`,
		  ADD COLUMN `location_detail` TEXT NULL AFTER `floor_no`,
		  ADD COLUMN `facilities_detail` TEXT NULL AFTER `location_detail`,
		  ADD COLUMN `seo_title` TEXT NULL AFTER `facilities_detail`,
		  ADD COLUMN `seo_keywords` TEXT NULL AFTER `seo_title`,
		  ADD COLUMN `seo_description` TEXT NULL AFTER `seo_keywords`,
		  ADD COLUMN `start_time` VARCHAR(32) NULL AFTER `seo_description`,
		  ADD COLUMN `estimate_finished` VARCHAR(32) NULL AFTER `start_time`,
		  ADD COLUMN `owner_type` VARCHAR(255) NULL AFTER `estimate_finished`,
		  ADD COLUMN `slug` VARCHAR(32) NOT NULL AFTER `progress`;");
		
		$this->execute("ALTER TABLE `ad_building_project`   
			CHANGE `commercial_leasing_area` `commercial_leasing_area` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NULL;");
		
		$this->execute("ALTER TABLE `ad_building_project`   
  			CHANGE `status` `status` TINYINT(1) DEFAULT 1  NULL;");
		
		$this->execute("ALTER TABLE `ad_building_project`   
  CHANGE `lng` `lng` FLOAT(10,6) NULL,
  CHANGE `lat` `lat` FLOAT(10,6) NULL;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_building_project`   
		  DROP COLUMN `logo`, 
		  DROP COLUMN `location_detail`,
		  DROP COLUMN `facilities_detail`,
		  DROP COLUMN `seo_title`,
		  DROP COLUMN `seo_keywords`,
		  DROP COLUMN `seo_description`,
		  DROP COLUMN `start_time`, 
		  DROP COLUMN `estimate_finished`, 
		  DROP COLUMN `owner_type`,
          DROP COLUMN `slug`;");
        
		$this->execute("ALTER TABLE `ad_building_project`   
			CHANGE `commercial_leasing_area` `commercial_leasing_area` VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NULL;");
		
		$this->execute("ALTER TABLE `ad_building_project`   
			CHANGE `status` `status` TINYINT(1) NULL;");
		

		$this->execute("ALTER TABLE `ad_building_project`   
  CHANGE `lng` `lng` FLOAT NULL,
  CHANGE `lat` `lat` FLOAT NULL;");
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
