<?php

use yii\db\Schema;
use yii\db\Migration;

class m151125_075702_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_investor`   
  CHANGE `name` `name` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci NOT NULL;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_investor`   
  CHANGE `name` `name` VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NOT NULL;");
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
