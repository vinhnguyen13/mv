<?php

use yii\db\Schema;
use yii\db\Migration;

class m160704_102251_add_ip_column_ad_product extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product` ADD COLUMN `ip` VARCHAR(255) NULL AFTER `boost_time`;");
    }

    public function down()
    {
        $this->dropColumn('ad_product', 'ip');
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
