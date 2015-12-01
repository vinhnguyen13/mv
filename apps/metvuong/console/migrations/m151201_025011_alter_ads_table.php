<?php

use yii\db\Schema;
use yii\db\Migration;

class m151201_025011_alter_ads_table extends Migration
{
    public function up()
    {
		$this->dropTable('ad_product_geocoding');
		$this->execute("ALTER TABLE `metvuong`.`ad_product`   
			ADD COLUMN `lng` FLOAT(10,6) NULL AFTER `price_type`,
			ADD COLUMN `lat` FLOAT(10,6) NULL AFTER `lng`;");
    }

    public function down()
    {
    	$this->execute("CREATE TABLE `ad_product_geocoding` (
			`product_id` int(11) DEFAULT NULL,
			`lng` float NOT NULL,
			`lat` float NOT NULL,
			UNIQUE KEY `ad_product_geocoding:product_id&product:id` (`product_id`),
			CONSTRAINT `ad_product_geocoding:product_id&product:id` FOREIGN KEY (`product_id`) REFERENCES `ad_product` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("ALTER TABLE `metvuong`.`ad_product`   
			DROP COLUMN `lng`, 
			DROP COLUMN `lat`;");
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
