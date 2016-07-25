<?php

use yii\db\Migration;

class m160725_034626_add_tracking_search extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `tracking_search`(  
		  `id` BIGINT NOT NULL AUTO_INCREMENT,
		  `user_id` INT,
		  `session` VARCHAR(32),
		  `ip` VARCHAR(255),
		  `type` TINYINT(1),
		  `location` VARCHAR(255),
		  `city_id` INT,
		  `district_id` INT,
		  `ward_id` INT,
		  `street_id` INT,
		  `project_building_id` INT,
		  `category_id` VARCHAR(32),
		  `room_no` INT,
		  `toilet_no` INT,
		  `price_min` BIGINT,
		  `price_max` BIGINT,
		  `size_min` INT,
		  `size_max` INT,
		  `created_at` INT,
		  PRIMARY KEY (`id`)
		);");
    }

    public function down()
    {
        $this->dropTable('tracking_search');
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
