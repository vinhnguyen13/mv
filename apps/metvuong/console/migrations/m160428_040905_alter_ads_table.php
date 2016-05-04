<?php

use yii\db\Migration;

class m160428_040905_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `ad_category_group`(
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `name` VARCHAR(32) NOT NULL,
		  `categories_id` VARCHAR(32),
		  `order` INT NOT NULL DEFAULT 0,
		  `apply_to_type` TINYINT(1) NOT NULL DEFAULT 3,
		  `status` TINYINT(1) NOT NULL DEFAULT 1,
		  PRIMARY KEY (`id`)
		);");
    }

    public function down()
    {
		$this->dropTable("ad_category_group");
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
