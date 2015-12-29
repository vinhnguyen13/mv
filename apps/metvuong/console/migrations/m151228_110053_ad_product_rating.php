<?php

use yii\db\Schema;
use yii\db\Migration;

class m151228_110053_ad_product_rating extends Migration
{
    private $table = 'ad_product_rating';
    public function up()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(!is_object($table_check)) {
            $this->execute("CREATE TABLE `ad_product_rating` (
            `user_id` int(11) NOT NULL,
            `product_id` int(11) NOT NULL,
            `core` decimal(5,0) NOT NULL DEFAULT '0',
            `rating_at` int(11) NOT NULL,
            PRIMARY KEY (`user_id`,`product_id`),
            KEY `prouct_id` (`product_id`),
            CONSTRAINT `ad_product_rating_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ad_product` (`id`),
            CONSTRAINT `ad_product_rating_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        }

    }

    public function down()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(is_object($table_check)) {
            $this->dropTable('ad_product_rating');
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
