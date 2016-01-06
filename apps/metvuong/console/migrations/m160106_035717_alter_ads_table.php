<?php

use yii\db\Schema;
use yii\db\Migration;

class m160106_035717_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_images`   
  ADD COLUMN `order` INT NULL AFTER `uploaded_at`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_images`   
  DROP COLUMN `order`;");
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
