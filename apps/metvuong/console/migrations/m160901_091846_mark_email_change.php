<?php

use yii\db\Migration;

class m160901_091846_mark_email_change extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `mark_email`
          MODIFY id INT(11) AUTO_INCREMENT FIRST;");
    }

    public function down()
    {
        echo "m160901_091846_mark_email_change cannot be reverted.\n";

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
