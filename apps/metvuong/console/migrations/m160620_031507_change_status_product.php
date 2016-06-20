<?php

use yii\db\Schema;
use yii\db\Migration;

class m160620_031507_change_status_product extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product` CHANGE `status` `status` TINYINT(1) DEFAULT 1  NOT NULL  COMMENT '1: hiện tin, 0: ẩn tin, -1 pending';");
    }

    public function down()
    {
		$this->execute("ALTER TABLE `ad_product` CHANGE `status` `status` TINYINT(1) DEFAULT 1  NOT NULL  COMMENT '1: hiện tin, 0: ẩn tin';");
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
