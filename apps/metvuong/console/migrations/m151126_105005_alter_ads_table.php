<?php

use yii\db\Schema;
use yii\db\Migration;

class m151126_105005_alter_ads_table extends Migration
{
    public function up()
    {
    	$this->execute("ALTER TABLE `ad_city`
			ADD COLUMN `code` VARCHAR(4) NOT NULL AFTER `id`,
			ADD COLUMN `order` INT NULL AFTER `name`;");
		
		$this->execute("ALTER TABLE `ad_district`   
			ADD COLUMN `pre` VARCHAR(32) NULL AFTER `name`,
			ADD COLUMN `order` INT NULL AFTER `pre`;");
		
		$this->execute("ALTER TABLE `ad_ward`   
			ADD COLUMN `pre` VARCHAR(32) NULL AFTER `name`,
			ADD COLUMN `order` INT NULL AFTER `pre`;");
		
		$this->execute("ALTER TABLE `ad_street`   
			DROP COLUMN `ward_id`, 
			ADD COLUMN `pre` VARCHAR(32) NULL AFTER `name`,
			ADD COLUMN `order` INT NULL AFTER `pre`, 
			DROP INDEX `street:ward_id&ward:id`,
			DROP FOREIGN KEY `street:ward_id&ward:id`;");
		
		$this->execute("CREATE TABLE `ad_building_project_category`(  
			`building_project_id` INT NOT NULL,
			`category_id` INT NOT NULL,
			CONSTRAINT `building_project_id&building_project-id` FOREIGN KEY (`building_project_id`) REFERENCES `ad_building_project`(`id`),
			CONSTRAINT `category_id&category:id` FOREIGN KEY (`category_id`) REFERENCES `ad_category`(`id`));");
		
		$data = include 'data.php';
		$cities = json_decode($data);
		
		foreach ($cities as $k => $city) {
			$insertCitySql = "INSERT INTO `ad_city` (`code`, `name`) VALUES (" . $this->db->quoteValue($city->code) . ", " . $this->db->quoteValue($city->name) . ")";
			$this->execute($insertCitySql);
			$cityId = $this->db->lastInsertID;
		
			foreach($city->district as $district) {
				$insertDistrictSql = "INSERT INTO `ad_district` (`city_id`, `name`, `pre`) VALUES ($cityId, " . $this->db->quoteValue($district->name) . ", " . $this->db->quoteValue($district->pre) . ")";
				$this->execute($insertDistrictSql);
				$districtId = $this->db->lastInsertID;
				 
				if(!empty($district->ward)) {
					$insertSql = "INSERT INTO `ad_ward` (`district_id`, `name`, `pre`) VALUES ";
					$insertValues = [];
					foreach($district->ward as $ward) {
						$insertValues[] = "($districtId, " . $this->db->quoteValue($ward->name) . ", " . $this->db->quoteValue($ward->pre) . ")";
					}
					$insertSql .= implode(', ', $insertValues);
					$this->execute($insertSql);
				}
				
				if(!empty($district->street)) {
					$insertSql = "INSERT INTO `ad_street` (`district_id`, `name`, `pre`) VALUES ";
					$insertValues = [];
					foreach($district->street as $street) {
						$insertValues[] = "($districtId, " . $this->db->quoteValue($street->name) . ", " . $this->db->quoteValue($street->pre) . ")";
					}
					$insertSql .= implode(', ', $insertValues);
					$this->execute($insertSql);
				}
			}
		}
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_city`   
			DROP COLUMN `code`, 
			DROP COLUMN `order`;");
        
        $this->execute("ALTER TABLE `ad_district`   
			DROP COLUMN `pre`, 
			DROP COLUMN `order`;");
        
        $this->execute("ALTER TABLE `ad_ward`   
			DROP COLUMN `pre`, 
			DROP COLUMN `order`;");
        
        $this->execute("ALTER TABLE `ad_street`   
			DROP COLUMN `pre`, 
			DROP COLUMN `order`, 
			ADD COLUMN `ward_id` INT NOT NULL AFTER `district_id`,
			ADD CONSTRAINT `street:ward_id&ward:id` FOREIGN KEY (`ward_id`) REFERENCES `ad_ward`(`id`);");
        
        $this->dropTable("ad_building_project_category");
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
