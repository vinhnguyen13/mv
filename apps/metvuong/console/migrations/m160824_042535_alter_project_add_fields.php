<?php

use yii\db\Migration;

class m160824_042535_alter_project_add_fields extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_building_project`
                        ADD COLUMN `gfa` SMALLINT(6) NULL COMMENT 'Dien tich san' AFTER `click`,
                        ADD COLUMN `building_density` SMALLINT(6) NULL COMMENT 'Mat do xay dung' AFTER `gfa`,
                        ADD COLUMN `units_no` SMALLINT(6) NULL COMMENT '' AFTER `building_density`,
                        ADD COLUMN `no_1_bed` SMALLINT(6) NULL COMMENT '' AFTER `units_no`,
                        ADD COLUMN `sqm_1_bed` FLOAT NULL COMMENT '' AFTER `no_1_bed`,
                        ADD COLUMN `no_2_bed` SMALLINT(6) NULL COMMENT '' AFTER `sqm_1_bed`,
                        ADD COLUMN `sqm_2_bed` FLOAT NULL COMMENT '' AFTER `no_2_bed`,
                        ADD COLUMN `no_3_bed` SMALLINT(6) NULL COMMENT '' AFTER `sqm_2_bed`,
                        ADD COLUMN `sqm_3_bed` FLOAT NULL COMMENT '' AFTER `no_3_bed`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_building_project`
                        DROP COLUMN `sqm_3_bed`,
                        DROP COLUMN `no_3_bed`,
                        DROP COLUMN `sqm_2_bed`,
                        DROP COLUMN `no_2_bed`,
                        DROP COLUMN `sqm_1_bed`,
                        DROP COLUMN `no_1_bed`,
                        DROP COLUMN `units_no`,
                        DROP COLUMN `building_density`,
                        DROP COLUMN `gfa`;");
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
