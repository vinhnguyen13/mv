<?php

use yii\db\Migration;

class m160901_075346_mark_email_change extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `mark_email` DROP PRIMARY KEY;");
    }

    public function down()
    {
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
