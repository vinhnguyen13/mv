<?php

use yii\db\Schema;
use yii\db\Migration;

class m151120_065452_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `area_type`(  
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `building_project_id` INT NOT NULL,
		  `type` TINYINT(1) NOT NULL COMMENT '1: Khu căn hộ, 2: Khu nhà phố, 3: khu thương mại, 4: khu officetel',
		  `floor_plan` TEXT,
		  `payment` TEXT,
		  `promotion` TEXT,
		  `document` TEXT,
		  PRIMARY KEY (`id`),
		  CONSTRAINT `building_project_id:ad_building_project:id` FOREIGN KEY (`building_project_id`) REFERENCES `ad_building_project`(`id`)
		);");
		$this->execute("ALTER TABLE `ad_building_project`   
		  DROP COLUMN `apartment_area`, 
		  DROP COLUMN `commercial_area`, 
		  DROP COLUMN `townhouse_area`, 
		  DROP COLUMN `office_area`;");
    }

    public function down()
    {
        $this->dropTable("`area_type`");
        $this->execute("ALTER TABLE `metvuong`.`ad_building_project`   
		  ADD COLUMN `apartment_area` TEXT NULL AFTER `progress`,
		  ADD COLUMN `commercial_area` TEXT NULL AFTER `apartment_area`,
		  ADD COLUMN `townhouse_area` TEXT NULL AFTER `commercial_area`,
		  ADD COLUMN `office_area` TEXT NULL AFTER `townhouse_area`;");
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
