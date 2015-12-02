<?php

use yii\db\Schema;
use yii\db\Migration;

class m151202_065632_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product`   
			CHANGE `type` `type` TINYINT(1) NOT NULL  COMMENT '1: nhà đất bán, 2: nhà đất cho thuê';");
		$this->execute("ALTER TABLE `ad_category`   
			ADD COLUMN `apply_to_type` TINYINT(1) NOT NULL  COMMENT '1: nhà đất bán, 2: nhà đất cho thuê; 3: cả hai' AFTER `name`;");
    }

    public function down()
    {
		$this->execute("ALTER TABLE `ad_product`   
			CHANGE `type` `type` TINYINT(1) NOT NULL  COMMENT '1: nhà đất bán, 2: nhà đất cho thuê, 3: cần mua, 4: cần thuê';");
		$this->dropColumn('ad_category', 'apply_to_type');
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
