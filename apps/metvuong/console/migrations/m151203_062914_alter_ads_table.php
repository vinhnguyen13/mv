<?php

use yii\db\Schema;
use yii\db\Migration;

class m151203_062914_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product` ADD COLUMN `home_no` VARCHAR(32) NOT NULL AFTER `project_building_id`;");
		$this->execute("ALTER TABLE `ad_product` CHANGE `area` `area` FLOAT NULL;");
    }

    public function down()
    {
        $this->dropColumn('ad_product', 'home_no');
        $this->execute("ALTER TABLE `ad_product` CHANGE `area` `area` INT NULL;");
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
