<?php

use yii\db\Migration;

class m160824_091718_alter_project_number extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_building_project`
CHANGE COLUMN `apartment_no` `apartment_no` SMALLINT(6) NULL DEFAULT NULL COMMENT '' ,
CHANGE COLUMN `floor_no` `floor_no` SMALLINT(6) NULL DEFAULT NULL COMMENT '' ,
CHANGE COLUMN `start_time` `start_time` VARCHAR(100) NULL DEFAULT NULL COMMENT '' ,
CHANGE COLUMN `estimate_finished` `estimate_finished` VARCHAR(100) NULL DEFAULT NULL COMMENT '' ;");
    }

    public function down()
    {

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
