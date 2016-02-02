<?php

use yii\db\Schema;
use yii\db\Migration;

class m160129_043939_ad_contractor_table extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `ad_contractor` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `name` varchar(255) NOT NULL,
                          `address` varchar(255) DEFAULT NULL,
                          `phone` varchar(32) DEFAULT NULL,
                          `fax` varchar(32) DEFAULT NULL,
                          `website` varchar(255) DEFAULT NULL,
                          `email` varchar(255) DEFAULT NULL,
                          `description` varchar(1022) DEFAULT NULL,
                          `created_at` int(11) NOT NULL,
                          `updated_at` int(11) DEFAULT NULL,
                          `status` tinyint(1) NOT NULL DEFAULT '1',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->execute("CREATE TABLE `ad_contractor_building_project` (
                          `building_project_id` int(11) DEFAULT NULL,
                          `contractor_id` int(11) DEFAULT NULL,
                        CONSTRAINT `fk_building_project_id` FOREIGN KEY (`building_project_id`) REFERENCES `ad_building_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                        CONSTRAINT `fk_contractor_id` FOREIGN KEY (`contractor_id`) REFERENCES `ad_contractor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                        UNIQUE INDEX `building_project_id&contractor_id` (`building_project_id` ASC, `contractor_id` ASC)  COMMENT '',
                        INDEX `fk_building_project_id_idx` (`building_project_id` ASC)  COMMENT '',
                        INDEX `fk_contractor_id_idx` (`contractor_id` ASC)  COMMENT ''
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->dropTable("ad_contractor_building_project");
        $this->dropTable("ad_contractor");
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
