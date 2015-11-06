<?php
use yii\db\Schema;
use yii\db\Migration;

class m151103_090004_create_lc_building_translation extends Migration
{
    public function up()
    {
    	$this->execute("CREATE TABLE `lancaster`.`lc_building_translation`(  
			`id` INT,
			`language_id` VARCHAR(5),
			`building_name` VARCHAR(32),
			`address` VARCHAR(255),
			`introduction_title` VARCHAR(1024),
			`introduction_content` VARCHAR(1024),
			CONSTRAINT `lc_building_translation:id&lc_building:id` FOREIGN KEY (`id`) REFERENCES `lancaster`.`lc_building`(`id`)
		);");

    	$this->execute("DELETE FROM `lancaster`.`language_translate`;");
    	$this->execute("DELETE FROM `lancaster`.`language_source`;");

    	$this->execute("UPDATE `lancaster`.`language` SET `status` = 0;");
    	$this->execute("UPDATE `lancaster`.`language` SET `status` = 1 WHERE `language_id` = 'en-US' OR `language_id` = 'vi-VN';");
    	
    	$buildings = $this->getAllBuilding();
    	$activeLangs = $this->getActiveLangs();
    	
    	foreach ($activeLangs as $activeLang) {
    		foreach ($buildings as $building) {
    			$sql = "INSERT INTO `lancaster`.`lc_building_translation` VALUES (" . $building['id'] . ", '" . $activeLang['language_id'] . "', '" . $building['building_name'] . "', '" . $building['address'] . "', 'Lancaster Legacy offers you a sweeping panoramic view of the city skyline', 'Besides 109 ultra-luxury and graciously furnished apartments ranging from studios to penthouses, the building also features 6 floors of working space for setting up professional and supreme offices')";
    			$this->execute($sql);
    		}
    	}
    }

    public function down()
    {
        $this->dropTable('`lancaster`.`lc_building_translation`');
    }
    
    public function getActiveLangs() {
    	$connection = \Yii::$app->db;
    	$model = $connection->createCommand('SELECT * FROM `lancaster`.`language` WHERE `status` = 1');
    	$languages = $model->queryAll();
    	 
    	return $languages;
    }
    
    public function getAllBuilding() {
    	$connection = \Yii::$app->db;
    	$model = $connection->createCommand('SELECT * FROM `lancaster`.`lc_building`');
    	$buildings = $model->queryAll();
    	
    	return $buildings;
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
