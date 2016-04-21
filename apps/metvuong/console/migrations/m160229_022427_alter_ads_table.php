<?php

use yii\db\Schema;
use yii\db\Migration;

class m160229_022427_alter_ads_table extends Migration
{
    public function up()
    {
    	$this->execute("ALTER TABLE `ad_product` ADD COLUMN `owner` TINYINT(1) DEFAULT 1  NOT NULL  COMMENT 'BĐS trong tin đăng thuộc sở hữu bởi -> 1: Chủ nhà, 2: môi giới.' AFTER `rating`;");
    }

    public function down()
    {
    	$this->execute("ALTER TABLE `ad_product` DROP COLUMN `owner`;");
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
