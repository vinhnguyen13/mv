<?php

use yii\db\Schema;
use yii\db\Migration;

class m160225_015202_alter_building_project extends Migration
{

    public function up()
    {
        $this->execute("ALTER TABLE `ad_building_project`
                            CHANGE COLUMN `name` `name` VARCHAR(100) NOT NULL COMMENT '' ,
                            CHANGE COLUMN `logo` `logo` VARCHAR(255) NULL COMMENT '' ,
                            CHANGE COLUMN `description` `description` TEXT NULL DEFAULT NULL COMMENT '' ,
                            CHANGE COLUMN `slug` `slug` VARCHAR(255) NOT NULL COMMENT '' ;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_building_project`
                            CHANGE COLUMN `name` `name` VARCHAR(60) NOT NULL COMMENT '' ,
                            CHANGE COLUMN `logo` `logo` VARCHAR(100) NULL COMMENT '' ,
                            CHANGE COLUMN `description` `description` TEXT NULL DEFAULT NULL COMMENT '' ,
                            CHANGE COLUMN `slug` `slug` VARCHAR(100) NOT NULL COMMENT '' ;");
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
