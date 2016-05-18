<?php

use yii\db\Schema;
use yii\db\Migration;

class m160516_021632_alter_ad_product_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product`   
			CHANGE `status` `status` TINYINT(1) DEFAULT 1  NOT NULL  COMMENT '1: hiện tin, 0: ẩn tin',
			CHANGE `show_home_no` `show_home_no` TINYINT(1) DEFAULT 1  NOT NULL  COMMENT '0: ẩn số nhà, 1: hiển thị số nhà',
			ADD COLUMN `is_expired` TINYINT(1) DEFAULT 0  NULL  COMMENT '0: Còn hạn, 1: hết hạn -> chỉ lấy những tin có status là 0' AFTER `show_home_no`;");
    }

    public function down()
    {
        $this->dropColumn('ad_product', 'is_expired');
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
