<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ec_transaction_history`.
 */
class m160606_110033_create_ec_transaction_history extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("CREATE TABLE `ec_transaction_history` (
                          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                          `user_id` int(11) DEFAULT NULL,
                          `object_id` int(11) DEFAULT NULL,
                          `object_type` tinyint(4) DEFAULT NULL,
                          `amount` decimal(13,2) DEFAULT NULL,
                          `action_type` tinyint(4) DEFAULT NULL COMMENT '1: buy ( - amount)\n2: receive (+ amount)',
                          `action_detail` tinyint(4) DEFAULT NULL COMMENT '1: Post\n2: Boost\n3: View user dashboard\n4: Transfer amount',
                          `charge_id` int(11) DEFAULT NULL,
                          `status` tinyint(4) DEFAULT '1',
                          `params` text DEFAULT 'json',
                          `created_at` int(11) DEFAULT NULL,
                          `updated_at` int(11) DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `fk_ec_transaction_charge_idx` (`charge_id`),
                          KEY `fk_ec_transaction_user_idx` (`user_id`),
                          CONSTRAINT `fk_ec_transaction_charge` FOREIGN KEY (`charge_id`) REFERENCES `ec_charge` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
                          CONSTRAINT `fk_ec_transaction_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Transactions Log';");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ec_transaction_history');
    }
}
