<?php

use yii\db\Migration;

class m160826_073137_mark_email_change extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `mark_email`
        ADD COLUMN `read_time` int(11) DEFAULT '0',
        ADD COLUMN `click_time` int(11) DEFAULT '0',
        ADD COLUMN `ip` varchar(40) DEFAULT NULL
        ;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `mark_email`
                        DROP COLUMN `read_time`,
                        DROP COLUMN `click_time`,
                        DROP COLUMN `ip`;");
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
