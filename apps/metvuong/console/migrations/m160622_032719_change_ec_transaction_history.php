<?php

use yii\db\Migration;

class m160622_032719_change_ec_transaction_history extends Migration
{
    public function up()
    {
        $this->dropColumn('ec_transaction_history', 'action_type');
        $this->dropColumn('ec_transaction_history', 'action_detail');
        $this->execute("ALTER TABLE `ec_transaction_history`
        ADD COLUMN `code` varchar(255) NULL DEFAULT 0 AFTER `id`,
        ADD COLUMN `balance` bigint(20) NULL DEFAULT 0 AFTER `amount`;");
    }

    public function down()
    {
        $this->dropColumn('ec_transaction_history', 'code');
        $this->dropColumn('ec_transaction_history', 'balance');

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
