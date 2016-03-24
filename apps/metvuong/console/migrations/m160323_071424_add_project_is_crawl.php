<?php

use yii\db\Migration;

class m160323_071424_add_project_is_crawl extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_building_project`
                                ADD COLUMN `file_name` VARCHAR(32) NULL COMMENT '' AFTER `status`,
                                ADD COLUMN `is_crawl` TINYINT(1) NULL DEFAULT 0 COMMENT '' AFTER `file_name`,
                                ADD COLUMN `data_html` TEXT NULL COMMENT '' AFTER `is_crawl`;");
        if (count(Yii::$app->dbCraw) > 0) {
            $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project`
                                ADD COLUMN `file_name` VARCHAR(32) NULL COMMENT '' AFTER `status`,
                                ADD COLUMN `is_crawl` TINYINT(1) NULL DEFAULT 0 COMMENT '' AFTER `file_name`,
                                ADD COLUMN `data_html` TEXT NULL COMMENT '' AFTER `is_crawl`;");
        }
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_building_project`
                                DROP COLUMN `data_html`,
                                DROP COLUMN `is_crawl`,
                                DROP COLUMN `file_name`;");
        if (count(Yii::$app->dbCraw) > 0) {
            $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project`
                                DROP COLUMN `data_html`,
                                DROP COLUMN `is_crawl`,
                                DROP COLUMN `file_name`;");
        }
    }

}
