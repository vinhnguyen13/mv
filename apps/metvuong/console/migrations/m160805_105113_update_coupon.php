<?php

use yii\db\Migration;

class m160805_105113_update_coupon extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `cp_event` ADD COLUMN `type` TINYINT(1) DEFAULT 1 NULL AFTER `status`;");
        $this->execute("ALTER TABLE `cp_code` ADD COLUMN `limit` INT(11) DEFAULT 0 NULL AFTER `count`, DROP COLUMN `type`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `cp_event` DROP COLUMN `type`;");
        $this->execute("ALTER TABLE `cp_code` DROP COLUMN `limit`, ADD COLUMN `type` TINYINT(1) NULL AFTER `count`;");
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
