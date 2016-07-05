<?php

use yii\db\Schema;
use yii\db\Migration;

class m160704_102111_add_auto_save_table extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `ad_product_auto_save`(  
			  `id` INT NOT NULL AUTO_INCREMENT,
			  `category_id` INT,
			  `project_building_id` INT,
			  `home_no` VARCHAR(32),
			  `user_id` INT,
			  `city_id` INT,
			  `district_id` INT,
			  `ward_id` INT,
			  `street_id` INT,
			  `type` TINYINT,
			  `content` VARCHAR(3200),
			  `area` FLOAT,
			  `price` BIGINT(20),
			  `lng` FLOAT(10,6),
			  `lat` FLOAT(10,6),
			  `created_at` INT,
			  `updated_at` INT,
			  `owner` TINYINT(1),
			  `show_home_no` TINYINT(1),
			  `facade_width` FLOAT,
			  `land_width` FLOAT,
			  `home_direction` TINYINT(4),
			  `facade_direction` TINYINT(4),
			  `floor_no` INT(11),
			  `room_no` INT(11),
			  `toilet_no` INT(11),
			  `interior` VARCHAR(3200),
			  `facility` VARCHAR(255),
			  `stay_time` INT,
			  `ip` VARCHAR(255),
			  `name` VARCHAR(32),
			  `address` VARCHAR(255),
			  `phone` VARCHAR(32),
			  `mobile` VARCHAR(32),
			  `email` VARCHAR(32),
			  PRIMARY KEY (`id`)
			);");
    }

    public function down()
    {
        $this->dropTable('ad_product_auto_save');
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
