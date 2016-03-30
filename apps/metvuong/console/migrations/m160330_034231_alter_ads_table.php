<?php

use yii\db\Schema;
use yii\db\Migration;

class m160330_034231_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `metvuong`.`ad_street`   
  ADD COLUMN `geometry` TEXT NULL AFTER `pre`,
  ADD COLUMN `center` VARCHAR(32) NULL AFTER `geometry`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `metvuong`.`ad_street`   
  DROP COLUMN `geometry`, 
  DROP COLUMN `center`;");
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
