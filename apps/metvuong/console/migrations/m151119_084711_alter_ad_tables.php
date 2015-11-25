<?php

use yii\db\Schema;
use yii\db\Migration;

class m151119_084711_alter_ad_tables extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product`   
  			ADD COLUMN `verified` TINYINT(1) DEFAULT 0  NOT NULL  COMMENT '1: đã được duyệt, 0: chưa được kiểm duyệt' AFTER `view`;");
		$this->execute("ALTER TABLE `ad_category`   
		  CHANGE `order` `order` INT(11) NULL  AFTER `name`,
		  CHANGE `status` `status` TINYINT(1) DEFAULT 1  NULL  COMMENT '1: actived, 0: deactived'  AFTER `order`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product` DROP COLUMN `verified`;");
		$this->execute("ALTER TABLE `ad_category`   
		  CHANGE `status` `status` TINYINT(1) DEFAULT 1  NULL  COMMENT '1: actived, 0: deactived'  AFTER `name`,
		  CHANGE `order` `order` INT(11) NULL  AFTER `status`;");
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
