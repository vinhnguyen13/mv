<?php

use yii\db\Migration;

class m160526_033908_alter_user_table extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `user` ADD COLUMN `aliasname` VARCHAR(255) DEFAULT NULL AFTER `username`;");
        $this->execute("UPDATE `user` set `aliasname` = `username`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `user` DROP COLUMN `aliasname`;");
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
