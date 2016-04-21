<?php

use yii\db\Schema;
use yii\db\Migration;

class m160112_031941_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_ward`   
			ADD COLUMN `center` VARCHAR(32) NULL AFTER `geometry`,
			ADD COLUMN `color` VARCHAR(32) NULL AFTER `center`;");
		$this->execute("ALTER TABLE `ad_district`   
			ADD COLUMN `center` VARCHAR(32) NULL AFTER `geometry`,
			ADD COLUMN `color` VARCHAR(32) NULL AFTER `center`;");
		$this->execute("ALTER TABLE `ad_city`   
			ADD COLUMN `geometry` TEXT NULL AFTER `name`,
			ADD COLUMN `center` VARCHAR(32) NULL AFTER `geometry`,
			ADD COLUMN `color` VARCHAR(32) NULL AFTER `center`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_ward`   
			DROP COLUMN `center`, 
			DROP COLUMN `color`;");
        $this->execute("ALTER TABLE `ad_district`   
			DROP COLUMN `center`, 
			DROP COLUMN `color`;");
        $this->execute("ALTER TABLE `ad_city`   
			DROP COLUMN `geometry`, 
			DROP COLUMN `center`, 
			DROP COLUMN `color`;");
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
