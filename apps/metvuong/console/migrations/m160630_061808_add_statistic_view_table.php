<?php

use yii\db\Schema;
use yii\db\Migration;

class m160630_061808_add_statistic_view_table extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `ec_statistic_view`(  
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `user_id` INT NOT NULL,
		  `start_at` INT NOT NULL,
		  `end_at` INT NOT NULL,
		  PRIMARY KEY (`id`),
		  CONSTRAINT `ec_statistic_view:user_id&user:id` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`)
		);");
    }

    public function down()
    {
        $this->dropTable('ec_statistic_view');
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
