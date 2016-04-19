<?php

use yii\db\Schema;
use yii\db\Migration;

class m160419_033740_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `metvuong`.`ad_product_addition_info`   
						DROP COLUMN `addition_fields`, 
						ADD COLUMN `facility` VARCHAR(255) NULL AFTER `interior`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `metvuong`.`ad_product_addition_info`   
						DROP COLUMN `facility`, 
						ADD COLUMN `addition_fields` TEXT NULL AFTER `interior`;");
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
