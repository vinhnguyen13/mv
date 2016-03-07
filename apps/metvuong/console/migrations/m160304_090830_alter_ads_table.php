<?php

use yii\db\Schema;
use yii\db\Migration;

class m160304_090830_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product`   
			CHANGE `type` `type` TINYINT(1) DEFAULT 1  NOT NULL  COMMENT '1: nhà đất bán, 2: nhà đất cho thuê',
			CHANGE `show_home_no` `show_home_no` TINYINT(1) DEFAULT 1  NOT NULL  COMMENT '0: ẩn số nhà, 1 -> hiển thị số nhà';");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product`   
			CHANGE `type` `type` TINYINT(1) NOT NULL  COMMENT '1: nhà đất bán, 2: nhà đất cho thuê',
			CHANGE `show_home_no` `show_home_no` TINYINT(1) NOT NULL  COMMENT '0: ẩn số nhà, 1 -> hiển thị số nhà';");
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
