<?php

use yii\db\Schema;
use yii\db\Migration;

class m151209_064323_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product` CHANGE `price` `price` BIGINT NULL;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product` CHANGE `price` `price` INT NULL;");
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
