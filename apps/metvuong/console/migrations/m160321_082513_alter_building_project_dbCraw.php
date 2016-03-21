<?php

use yii\db\Migration;

class m160321_082513_alter_building_project_dbCraw extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_investor_building_project`
                        DROP FOREIGN KEY `building_project_id&building_project:id`,
                        DROP FOREIGN KEY `investor_id&investor:id`;
                        ALTER TABLE `ad_investor_building_project`
                        ADD CONSTRAINT `building_project_id&building_project:id`
                          FOREIGN KEY (`building_project_id`)
                          REFERENCES `ad_building_project` (`id`)
                          ON DELETE CASCADE
                          ON UPDATE CASCADE,
                        ADD CONSTRAINT `investor_id&investor:id`
                          FOREIGN KEY (`investor_id`)
                          REFERENCES `ad_investor` (`id`)
                          ON DELETE CASCADE
                          ON UPDATE CASCADE;");

        if (count(Yii::$app->dbCraw) > 0) {
            $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project` ADD COLUMN `file_name` VARCHAR(32) NULL DEFAULT NULL COMMENT 'crawl file name' AFTER `status`;");
        }
    }

    public function down()
    {
        if (count(Yii::$app->dbCraw) > 0) {
            $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project` DROP COLUMN `file_name`;");
        }
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
