<?php

use yii\db\Schema;
use yii\db\Migration;

class m151124_075312_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `metvuong`.`ad_investor`
			ADD COLUMN `logo` VARCHAR(32) NULL AFTER `name`,
			ADD COLUMN `created_at` INT NOT NULL AFTER `email`,
			ADD COLUMN `updated_at` INT NULL AFTER `created_at`,
			ADD COLUMN `status` TINYINT(1) DEFAULT 1  NOT NULL AFTER `updated_at`;");
		
		$this->execute("ALTER TABLE `metvuong`.`ad_investor_building_project`   
  ADD  UNIQUE INDEX `building_project_id&investor_id` (`building_project_id`, `investor_id`);");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `metvuong`.`ad_investor`   
			DROP COLUMN `created_at`,
			DROP COLUMN `updated_at`,
			DROP COLUMN `logo`,
			DROP COLUMN `status`;");
        $this->execute("ALTER TABLE `metvuong`.`ad_investor_building_project`   
  DROP INDEX `building_project_id&investor_id`;");
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
