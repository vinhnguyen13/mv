<?php

use yii\db\Migration;

class m160401_070302_alter_add_click_building_project extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_building_project`
                            ADD COLUMN `hot_project` TINYINT(1) NULL DEFAULT 0 COMMENT '1 is hot project' AFTER `status`,
                            ADD COLUMN `click` INT NULL DEFAULT 0 COMMENT '' AFTER `hot_project`;");
    }

    public function down()
    {
        $this->dropColumn('ad_building_project', 'hot_project');
        $this->dropColumn('ad_building_project', 'click');
    }

}
