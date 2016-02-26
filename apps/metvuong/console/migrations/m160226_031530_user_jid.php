<?php

use yii\db\Schema;
use yii\db\Migration;

class m160226_031530_user_jid extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `user_jid` (
                        `user_id` int(11) NOT NULL,
                        `username` varchar(255) DEFAULT NULL,
                        `jid` varchar(2049) DEFAULT NULL,
                        `jid_id` int(11) DEFAULT NULL,
                        PRIMARY KEY (`user_id`),
                        CONSTRAINT `user_jid_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    public function down()
    {
        $this->dropTable("user_jid");
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
