<?php

use yii\db\Schema;
use yii\db\Migration;

class m151208_020145_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_category` ADD COLUMN `template` TINYINT(1) DEFAULT 1  NOT NULL AFTER `status`;");
    }

    public function down()
    {
        $this->dropColumn('ad_category', 'template');
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
