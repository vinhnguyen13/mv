<?php

use yii\db\Migration;

class m160521_094059_add_project_main_id_dbCraw extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project`
                            ADD COLUMN `project_main_id` INT NULL DEFAULT 0 COMMENT '' AFTER `ward_id`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `db_mv_tool`.`ad_building_project`
                          DROP COLUMN `project_main_id`;");
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
