<?php

use yii\db\Schema;
use yii\db\Migration;

class m151109_042522_alter_lc_building_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `lancaster`.`lc_building`   
  			ADD COLUMN `main_background` VARCHAR(255) NULL AFTER `floor`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `lc_building`   
			DROP COLUMN `main_background`;");
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
