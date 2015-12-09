<?php

use yii\db\Schema;
use yii\db\Migration;

class m151208_073131_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_product_addition_info`   
			CHANGE `facade_width` `facade_width` FLOAT(11) NULL,
			CHANGE `land_width` `land_width` FLOAT(11) NULL;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product_addition_info`   
			CHANGE `facade_width` `facade_width` INT NULL,
			CHANGE `land_width` `land_width` INT NULL;");
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
