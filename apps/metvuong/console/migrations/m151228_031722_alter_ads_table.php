<?php

use yii\db\Schema;
use yii\db\Migration;

class m151228_031722_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `ad_product_saved`(  
		  `user_id` INT NOT NULL,
		  `product_id` INT NOT NULL,
		  `saved_at` INT NOT NULL,
		  PRIMARY KEY (`user_id`, `product_id`),
		  CONSTRAINT `user_id&user:id` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`),
		  CONSTRAINT `prouct_id` FOREIGN KEY (`product_id`) REFERENCES `ad_product`(`id`)
		);");
    }

    public function down()
    {
        $this->dropTable('ad_product_saved');
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
