<?php

use yii\db\Schema;
use yii\db\Migration;

class m151130_021803_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `metvuong`.`ad_building_project`   
			ADD COLUMN `district_id` INT NULL AFTER `id`,
			ADD CONSTRAINT `district_id&district:id` FOREIGN KEY (`district_id`) REFERENCES `metvuong`.`ad_district`(`id`);");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `metvuong`.`ad_building_project`   
			DROP COLUMN `district_id`, 
			DROP INDEX `district_id&district:id`,
			DROP FOREIGN KEY `district_id&district:id`;");
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
