<?php

use yii\db\Migration;

class m160802_033229_alter_tracking_search extends Migration
{
    public function up()
    {
		$this->execute("RENAME TABLE `tracking_search` TO `tracking_search_backup`;");
		$this->execute("CREATE TABLE `tracking_search`(  
		  `id` BIGINT NOT NULL AUTO_INCREMENT,
		  `user_id` INT,
		  `session` VARCHAR(32),
		  `ip` VARCHAR(255),
		  `alias` VARCHAR(255),
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
		  `order_by` VARCHAR(32),
		  `referer` VARCHAR(512),
		  `agent` VARCHAR(255),
		  `is_mobile` TINYINT(1),
		  `from` TINYINT(1),
		  `created_at` INT,
		  PRIMARY KEY (`id`)
		);");
    }

    public function down()
    {
        $this->dropTable('tracking_search');
        $this->execute("RENAME TABLE `tracking_search_backup` TO `tracking_search`;");
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
