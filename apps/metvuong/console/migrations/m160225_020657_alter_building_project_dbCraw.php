<?php

use yii\db\Schema;
use yii\db\Migration;

class m160225_020657_alter_building_project_dbCraw extends Migration
{
    public function up()
    {
        if(empty(Yii::$app->get('dbCraw'))){
            return false;
        }
        $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project` ADD COLUMN `description` TEXT NULL COMMENT '' AFTER `location`;");
        $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project`
                            CHANGE COLUMN `name` `name` VARCHAR(100) NOT NULL COMMENT '' ,
                            CHANGE COLUMN `logo` `logo` VARCHAR(255) NULL COMMENT '' ,
                            CHANGE COLUMN `slug` `slug` VARCHAR(255) NOT NULL COMMENT '' ;");

        $this->execute("CREATE TABLE `db_mv_tool`.`ad_contractor` (
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

        $this->execute("CREATE TABLE `db_mv_tool`.`ad_contractor_building_project` (
                          `building_project_id` int(11) DEFAULT NULL,
                          `contractor_id` int(11) DEFAULT NULL,
                        CONSTRAINT `fk_building_project_id` FOREIGN KEY (`building_project_id`) REFERENCES `ad_building_project` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                        CONSTRAINT `fk_contractor_id` FOREIGN KEY (`contractor_id`) REFERENCES `ad_contractor` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
                        UNIQUE INDEX `building_project_id&contractor_id` (`building_project_id` ASC, `contractor_id` ASC)  COMMENT '',
                        INDEX `fk_building_project_id_idx` (`building_project_id` ASC)  COMMENT '',
                        INDEX `fk_contractor_id_idx` (`contractor_id` ASC)  COMMENT ''
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project`
                            ADD COLUMN `start_date` INT NULL COMMENT '' AFTER `seo_description`,
                            ADD COLUMN `facade_width` FLOAT NULL COMMENT '' AFTER `facilities_detail`,
                            ADD COLUMN `lift` SMALLINT NULL COMMENT '' AFTER `facade_width`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project`
                            CHANGE COLUMN `name` `name` VARCHAR(60) NOT NULL COMMENT '' ,
                            CHANGE COLUMN `logo` `logo` VARCHAR(100) NULL COMMENT '' ,
                            CHANGE COLUMN `description` `description` TEXT NULL DEFAULT NULL COMMENT '' ,
                            CHANGE COLUMN `slug` `slug` VARCHAR(100) NOT NULL COMMENT '' ;");

        $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project`
                            DROP COLUMN `lift`,
                            DROP COLUMN `facade_width`,
                            DROP COLUMN `facilities_detail`;");

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
