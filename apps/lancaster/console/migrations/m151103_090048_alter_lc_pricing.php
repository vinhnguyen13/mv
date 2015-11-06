<?php

use yii\db\Schema;
use yii\db\Migration;

class m151103_090048_alter_lc_pricing extends Migration
{
    public function up()
    {
    	$pricings = $this->getAllPricing();
    	 
    	$this->execute("TRUNCATE TABLE `lc_pricing`;");
    	
    	$this->execute("ALTER TABLE `lancaster`.`lc_pricing`   
			ADD COLUMN `building_id` INT(11) NOT NULL AFTER `apart_type_id`,
			ADD CONSTRAINT `lc_pricing:building_id&lc_building:id` FOREIGN KEY (`building_id`) REFERENCES `lancaster`.`lc_building`(`id`);
		");
    	
    	$buildingMigration = new m151103_090004_create_lc_building_translation();
    	$buildings = $buildingMigration->getAllBuilding();
    	
    	foreach ($buildings as $building) {
    		foreach ($pricings as $pricing) {
    			$sql = "INSERT INTO `lancaster`.`lc_pricing` (`apart_type_id`, `building_id`,`area`, `monthly_rates`, `daily_rates`, `description`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
    				(". $pricing['apart_type_id'] . ", " . $building['id'] . ",". $pricing['area'] . ", ". $pricing['monthly_rates'] . ", ". $pricing['daily_rates'] . ", 
					'" . $pricing['description'] . "', '" . $pricing['created_at'] . "', '" . $pricing['updated_at'] . "', 1, 1);";
    			$this->execute($sql);
    		}
    	}
    }

    public function down()
    {
		$this->execute("ALTER TABLE `lancaster`.`lc_pricing`   
			DROP COLUMN `building_id`, 
			DROP INDEX `lc_pricing:building_id&lc_building:id`,
			DROP FOREIGN KEY `lc_pricing:building_id&lc_building:id`;");
    }
    
    public function getAllPricing() {
    	$connection = \Yii::$app->db;
    	$model = $connection->createCommand('SELECT * FROM `lancaster`.`lc_pricing`');
    	$pricings = $model->queryAll();
    	 
    	return $pricings;
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
