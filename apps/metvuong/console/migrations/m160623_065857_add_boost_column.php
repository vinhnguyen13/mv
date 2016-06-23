<?php

use yii\db\Schema;
use yii\db\Migration;

class m160623_065857_add_boost_column extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product` ADD COLUMN `boost_time` INT DEFAULT 0  NULL AFTER `is_expired`;");
    }

    public function down()
    {
        $this->dropColumn('ad_product', 'boost_time');
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
