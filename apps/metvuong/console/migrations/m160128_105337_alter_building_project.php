<?php

use yii\db\Schema;
use yii\db\Migration;

class m160128_105337_alter_building_project extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_building_project` ADD COLUMN `description` VARCHAR(1022) NULL COMMENT '' AFTER `location`;");
    }

    public function down()
    {
        $this->dropColumn('ad_building_project', 'description');
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
