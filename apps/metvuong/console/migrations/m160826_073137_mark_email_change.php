<?php

use yii\db\Migration;

class m160826_073137_mark_email_change extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `mark_email`
        ADD COLUMN `read_time` int(11) DEFAULT '0';");
    }

    public function down()
    {
        $this->dropColumn('read_time', 'updated_at');
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
