<?php

use yii\db\Schema;
use yii\db\Migration;

class m160303_105330_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product` ADD COLUMN `show_home_no` TINYINT(1) NOT NULL  COMMENT '0: ẩn số nhà, 1 -> hiển thị số nhà' AFTER `owner`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product` DROP COLUMN `show_home_no`;");
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
