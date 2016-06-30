<?php

use yii\db\Migration;

class m160630_111633_change_ec_transaction_history extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ec_transaction_history`
        MODIFY COLUMN `amount` int(11) DEFAULT 0");
    }

    public function down()
    {
        echo "m160630_111633_change_ec_transaction_history cannot be reverted.\n";

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
