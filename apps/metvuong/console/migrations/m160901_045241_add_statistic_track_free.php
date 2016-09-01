<?php

use yii\db\Migration;

class m160901_045241_add_statistic_track_free extends Migration
{
    public function up()
    {
		$this->execute("CREATE TABLE `ec_statistic_view_track`(  
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `statistic_id` INT NOT NULL,
		  `start_at` INT NOT NULL,
		  PRIMARY KEY (`id`),
		  CONSTRAINT `statistic_id&statistic:id` FOREIGN KEY (`statistic_id`) REFERENCES `metvuong`.`ec_statistic_view`(`id`)
		);");
    }

    public function down()
    {
        $this->dropTable('ec_statistic_view_track');
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
