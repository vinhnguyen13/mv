<?php

use yii\db\Migration;

class m160826_090137_add_display_name_project extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_building_project`
ADD COLUMN `bds_name` VARCHAR(100) NULL COMMENT 'ten du an hien thi tren metvuong.com' AFTER `name`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_building_project`
DROP COLUMN `bds_name`;");
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
