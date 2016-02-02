<?php

use yii\db\Schema;
use yii\db\Migration;

class m160201_080816_alter_building_project extends Migration
{
    public function up()
    {
            $this->execute("ALTER TABLE `ad_building_project`
                                ADD COLUMN `start_date` INT NULL COMMENT '' AFTER `seo_description`,
                                ADD COLUMN `facade_width` FLOAT NULL COMMENT '' AFTER `facilities_detail`,
                                ADD COLUMN `lift` SMALLINT NULL COMMENT '' AFTER `facade_width`;");
    }

    public function down()
    {
        $this->dropColumn("ad_building_project","facade_width");
        $this->dropColumn("ad_building_project","lift");
        $this->dropColumn("ad_building_project","start_date");
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
