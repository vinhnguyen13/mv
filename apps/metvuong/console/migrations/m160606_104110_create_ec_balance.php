<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ec_balance`.
 */
class m160606_104110_create_ec_balance extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("CREATE TABLE `ec_balance` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `user_id` int(11) DEFAULT NULL,
                          `amount` decimal(16,2) unsigned DEFAULT '0.00',
                          `amount_promotion` double(13,2) unsigned DEFAULT '0.00',
                          PRIMARY KEY (`id`),
                          KEY `fk_ec_balance_user_idx` (`user_id`),
                          CONSTRAINT `fk_ec_balance_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ec_balance');
    }
}
