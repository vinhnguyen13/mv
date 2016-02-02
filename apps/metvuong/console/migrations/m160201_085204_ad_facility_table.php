<?php

use yii\db\Schema;
use yii\db\Migration;

class m160201_085204_ad_facility_table extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `ad_facility` (
                          `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
                          `name` VARCHAR(255) NULL COMMENT '',
                          `description` VARCHAR(1022) NULL COMMENT '',
                          `created_at` INT(11) NULL DEFAULT NULL COMMENT '',
                          `updated_at` INT(11) NULL DEFAULT NULL COMMENT '',
                          `created_by` INT(11) NULL DEFAULT NULL COMMENT '',
                          `updated_by` INT(11) NULL COMMENT '',
                          `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '',
                          PRIMARY KEY (`id`)  COMMENT '',
                          UNIQUE INDEX `name_UNIQUE` (`name` ASC)  COMMENT '')
                        COMMENT = 'tiện ích';
                        ");
        $this->execute("CREATE TABLE `ad_facility_building_project` (
                          `building_project_id` int(11) DEFAULT NULL,
                          `facility_id` int(11) DEFAULT NULL,
                        CONSTRAINT `fk_building_project_facility` FOREIGN KEY (`building_project_id`) REFERENCES `ad_building_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                        CONSTRAINT `fk_facility_id` FOREIGN KEY (`facility_id`) REFERENCES `ad_facility` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                        UNIQUE INDEX `building_project_id&facility_id` (`building_project_id` ASC, `facility_id` ASC)  COMMENT '',
                        INDEX `fk_building_project_facility_idx` (`building_project_id` ASC)  COMMENT '',
                        INDEX `fk_facility_id_idx` (`facility_id` ASC)  COMMENT ''
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->dropTable("ad_facility");
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
