<?php

use yii\db\Schema;
use yii\db\Migration;

class m160302_080404_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_images` ADD COLUMN `folder` VARBINARY(255) NOT NULL AFTER `order`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_images` DROP COLUMN `folder`;");
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
