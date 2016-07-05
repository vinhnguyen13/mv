<?php

use yii\db\Schema;
use yii\db\Migration;

class m160704_103659_add_auto_save_images_table extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `ad_product_auto_save_images`(  
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `user_id` INT(11),
		  `product_id` INT(11),
		  `file_name` VARCHAR(255),
		  `uploaded_at` INT(11),
		  `order` INT(11) NOT NULL DEFAULT 0,
		  `folder` VARCHAR(255),
		  PRIMARY KEY (`id`)
		);");
    }

    public function down()
    {
        $this->dropTable('ad_product_auto_save_images');
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
