<?php

use yii\db\Schema;
use yii\db\Migration;

class m151207_031633_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product` ADD COLUMN `price_input` FLOAT NULL AFTER `price`;");
    }

    public function down()
    {
        $this->dropColumn('ad_product', 'price_input');
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
