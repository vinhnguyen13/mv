<?php

use yii\db\Migration;

class m160829_063532_alter_SQM_Bed_Project extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_building_project`
CHANGE COLUMN `sqm_1_bed` `sqm_1_bed` VARCHAR(255) NULL DEFAULT NULL COMMENT '' ,
CHANGE COLUMN `sqm_2_bed` `sqm_2_bed` VARCHAR(255) NULL DEFAULT NULL COMMENT '' ,
CHANGE COLUMN `sqm_3_bed` `sqm_3_bed` VARCHAR(255) NULL DEFAULT NULL COMMENT '' ;
");
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
