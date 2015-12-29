<?php

use yii\db\Schema;
use yii\db\Migration;

class m151228_105037_alter_ads_table extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_product`
		  ADD COLUMN `rating`  decimal(5,0) NOT NULL AFTER `source`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product`
			DROP COLUMN `rating`");
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
