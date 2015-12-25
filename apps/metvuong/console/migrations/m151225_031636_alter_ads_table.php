<?php

use yii\db\Schema;
use yii\db\Migration;

class m151225_031636_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_category` ADD COLUMN `limit_area` INT NULL AFTER `template`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_category` DROP COLUMN `limit_area`;");
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
