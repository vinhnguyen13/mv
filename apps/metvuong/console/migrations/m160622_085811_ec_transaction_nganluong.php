<?php

use yii\db\Migration;

class m160622_085811_ec_transaction_nganluong extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `ec_transaction_nganluong` (
                      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                      `token` varchar(255) DEFAULT NULL,
                      `transaction_code` varchar(255) DEFAULT NULL,
                      `amount` int(11) DEFAULT '0',
                      `buyer_fullname` varchar(255) DEFAULT NULL,
                      `buyer_email` varchar(255) DEFAULT NULL,
                      `buyer_mobile` varchar(30) DEFAULT NULL,
                      `bankcode` varchar(20) DEFAULT NULL,
                      `option_payment` varchar(20) DEFAULT NULL,
                      `status` tinyint(1) DEFAULT '0',
                      `created_at` int(11) DEFAULT '0',
                      `updated_at` int(11) DEFAULT '0',
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='Transactions NganLuong Log';");
    }

    public function down()
    {
        $this->dropTable('ec_transaction_nganluong');
        return false;
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
