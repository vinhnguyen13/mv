<?php

use yii\db\Schema;
use yii\db\Migration;

class m151231_042117_ad_product_report extends Migration
{
    public function up()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(!is_object($table_check)) {
            $this->execute("CREATE TABLE `ad_product_report` (
              `user_id` int(11) NOT NULL,
              `product_id` int(11) NOT NULL,
              `type` tinyint(1) NOT NULL DEFAULT '0',
              `description` varchar(500) DEFAULT NULL,
              `ip` varchar(40) DEFAULT NULL,
              `status` tinyint(2) DEFAULT NULL,
              `report_at` int(11) NOT NULL,
              PRIMARY KEY (`user_id`,`product_id`),
              KEY `prouct_id` (`product_id`),
              CONSTRAINT `ad_product_report_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ad_product` (`id`),
              CONSTRAINT `ad_product_report_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }
    }

    public function down()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(is_object($table_check)) {
            $this->dropTable('ad_product_report');
        }
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
