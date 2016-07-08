<?php

use yii\db\Migration;

class m160708_084924_email_bounces extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `email_bounces` (
              `email` VARCHAR(255) NOT NULL,
              `status` TINYINT NULL COMMENT '1: is fake, 0: not fake',
              `created_at` INT DEFAULT 0,
              `updated_at` INT DEFAULT 0,
              PRIMARY KEY (`email`)  COMMENT '')
            COMMENT = 'Mark emails has sent in Mail Marketing';");
    }

    public function down()
    {
        $this->dropTable('email_bounces');
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
