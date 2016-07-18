<?php

use yii\db\Migration;

class m160713_061857_add_boost_start_time extends Migration
{
	public function up()
    {
		$this->execute("ALTER TABLE `ad_product` ADD COLUMN `boost_start_time` INT NULL AFTER `boost_time`;");
    }

    public function down()
    {
        $this->dropColumn('ad_product', 'boost_start_time');
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
