<?php

use yii\db\Schema;
use yii\db\Migration;

class m151103_090032_alter_lc_building extends Migration
{
	private $map = [
		'Lancaster Legacy' => 'lancaster-legacy',
		'Lancaster Lê Thánh Tôn' => 'lancaster-le-thanh-ton',
		'Lancaster Nguyễn Trãi' => 'lancaster-nguyen-trai',
		'Lancaster Hà Nội' => 'lancaster-ha-noi',
		'Trung Thuy Group JSC' => 'trung-thuy-group-jsc'
	];
	
    public function up()
    {
    	$buildings = $this->getAllBuilding();
    	
    	$this->execute("ALTER TABLE `lc_building`   
			DROP COLUMN `building_name`,
			DROP COLUMN `address`,
			ADD COLUMN `slug` VARCHAR(32) NOT NULL AFTER `floor`,
			ADD COLUMN `apartments` TEXT NOT NULL AFTER `slug`,
			ADD COLUMN `amenities` TEXT NOT NULL AFTER `apartments`,
			ADD COLUMN `views` TEXT NOT NULL AFTER `amenities`,
			ADD COLUMN `neighborhood` TEXT NOT NULL AFTER `views`;");
		
		foreach ($buildings as $building) {
			$sql = "UPDATE `lc_building` SET `slug` = '" . $this->map[$building['building_name']] . "' WHERE `id` = " . $building['id'];
			$this->execute($sql);
		}
    }

    public function down()
    {
    	$buildings = $this->getAllBuilding();
    	
        $this->execute("ALTER TABLE `lc_building`   
			DROP COLUMN `slug`,
			DROP COLUMN `apartments`,
			DROP COLUMN `amenities`,
			DROP COLUMN `views`,
			DROP COLUMN `neighborhood`,
        	ADD COLUMN `building_name` VARCHAR(60) NOT NULL AFTER `id`,
			ADD COLUMN `address` VARCHAR(255) NULL AFTER `building_name`;");
        
        foreach ($buildings as $building) {
        	$sql = "UPDATE `lc_building` SET `building_name` = '" . array_search($building['slug'], $this->map) . "' WHERE `id` = " . $building['id'];
        	$this->execute($sql);
        }
    }

    public function getAllBuilding() {
    	$connection = \Yii::$app->db;
    	$model = $connection->createCommand('SELECT * FROM `lc_building`');
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
