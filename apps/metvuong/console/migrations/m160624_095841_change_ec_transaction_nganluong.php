<?php

use yii\db\Migration;

class m160624_095841_change_ec_transaction_nganluong extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ec_transaction_nganluong`
                            ADD COLUMN `payment_method` tinyint(1) DEFAULT 0 COMMENT '' AFTER `transaction_code`; ");
        $this->execute("ALTER TABLE `ec_transaction_nganluong`
                            ADD COLUMN `type_card` varchar(20) DEFAULT NULL COMMENT '' AFTER `option_payment`; ");
        $this->execute("ALTER TABLE `ec_transaction_nganluong`
                            ADD COLUMN `telco` varchar(20) DEFAULT NULL COMMENT '' AFTER `type_card`; ");
        $this->execute("ALTER TABLE `ec_transaction_nganluong`
                            ADD COLUMN `params` text DEFAULT NULL COMMENT '' AFTER `status`; ");
    }

    public function down()
    {
        $this->dropColumn('ec_transaction_nganluong', 'payment_method');
        $this->dropColumn('ec_transaction_nganluong', 'type_card');
        $this->dropColumn('ec_transaction_nganluong', 'telco');
        $this->dropColumn('ec_transaction_nganluong', 'params');
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
