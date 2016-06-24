<?php

use yii\db\Migration;

class m160624_090354_change_ec_blance extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ec_balance`
                            ADD COLUMN `created_at` int(11) DEFAULT 0 COMMENT '' AFTER `amount_promotion`; ");
        $this->execute("ALTER TABLE `ec_balance`
                            ADD COLUMN `updated_at` int(11) DEFAULT 0 COMMENT '' AFTER `created_at`; ");
    }

    public function down()
    {
//        `created_at` int(11) DEFAULT NULL,
//        `updated_at` int(11) DEFAULT NULL,
        $this->dropColumn('ec_balance', 'created_at');
        $this->dropColumn('ec_balance', 'updated_at');
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
