<?php

use yii\db\Schema;
use yii\db\Migration;

class m160229_081832_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product`  
  ADD CONSTRAINT `ad_product:project_building_id&ad_building_project:id` FOREIGN KEY (`project_building_id`) REFERENCES `ad_building_project`(`id`);");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product`  
  DROP FOREIGN KEY `ad_product:project_building_id&ad_building_project:id`;");
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
