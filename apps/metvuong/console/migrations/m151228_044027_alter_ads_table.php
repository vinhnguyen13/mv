<?php

use yii\db\Schema;
use yii\db\Migration;

class m151228_044027_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product`   
		  CHANGE `content` `content` VARCHAR(3200) CHARSET utf8 COLLATE utf8_general_ci NULL,
		  ADD COLUMN `source` TINYINT(1) NULL AFTER `status`;");
    }

    public function down()
    {
		$this->execute("ALTER TABLE `ad_product`   
			DROP COLUMN `source`, 
			CHANGE `content` `content` VARCHAR(3200) CHARSET utf8 COLLATE utf8_general_ci NOT NULL;");
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
