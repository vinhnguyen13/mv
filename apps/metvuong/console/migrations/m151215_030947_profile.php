<?php

use yii\db\Schema;
use yii\db\Migration;

class m151215_030947_profile extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `profile` ADD COLUMN `phone` VARCHAR(20) DEFAULT 0  NULL  AFTER `avatar`;");
        $this->execute("ALTER TABLE `profile` ADD COLUMN `mobile` VARCHAR(30) DEFAULT 0  NULL  AFTER `phone`;");
        $this->execute("ALTER TABLE `profile` ADD COLUMN `address` VARCHAR(255) DEFAULT NULL AFTER `mobile`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `profile` DROP COLUMN `phone`;");
        $this->execute("ALTER TABLE `profile` DROP COLUMN `mobile`;");
        $this->execute("ALTER TABLE `profile` DROP COLUMN `address`;");
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
