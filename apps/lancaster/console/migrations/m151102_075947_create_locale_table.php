<?php

use yii\db\Schema;
use yii\db\Migration;

class m151102_075947_create_locale_table extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `lancaster`.`lc_locale`(  
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `name` VARCHAR(32),
		  `language_id` VARCHAR(2),
		  `region_id` VARCHAR(2),
		  PRIMARY KEY (`id`)
		);");
		$this->execute("INSERT INTO `lancaster`.`lc_locale` (`name`, `language_id`, `region_id`) VALUES ('Tiếng Việt', 'vi', 'VN'), ('English', 'en', 'US');");
    }

    public function down()
    {
        $this->dropTable('`lancaster`.`lc_locale`');
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
