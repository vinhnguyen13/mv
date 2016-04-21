<?php

use yii\db\Schema;
use yii\db\Migration;

class m160405_085929_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product_addition_info`   
  						ADD COLUMN `addition_fields` TEXT NULL AFTER `interior`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product_addition_info`   
						DROP COLUMN `addition_fields`;");
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
