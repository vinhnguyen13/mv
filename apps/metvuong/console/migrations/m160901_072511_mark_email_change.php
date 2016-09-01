<?php

use yii\db\Migration;

class m160901_072511_mark_email_change extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `mark_email`
        CHANGE `ip` `read_ip` varchar(40) DEFAULT NULL,
        ADD COLUMN `click_ip` varchar(40) DEFAULT NULL
        ;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `mark_email`
                        DROP COLUMN `click_ip`,
                        DROP COLUMN `read_ip`;");
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
