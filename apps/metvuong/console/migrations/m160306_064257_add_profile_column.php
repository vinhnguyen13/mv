<?php

use yii\db\Migration;

class m160306_064257_add_profile_column extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `profile`
                            ADD COLUMN `owner` TINYINT(1) DEFAULT 2  NOT NULL  COMMENT 'BĐS trong tin đăng thuộc sở hữu bởi -> 1: Chủ nhà, 2: môi giới.' AFTER `slug`,
                            ADD COLUMN `first_name` VARCHAR(60) NULL COMMENT '',
                            ADD COLUMN `last_name` VARCHAR(60) NULL COMMENT '' AFTER `first_name`;");

    }

    public function down()
    {
        $this->execute("ALTER TABLE `profile` DROP COLUMN `owner`;");
        $this->execute("ALTER TABLE `profile` DROP COLUMN `first_name`;");
        $this->execute("ALTER TABLE `profile` DROP COLUMN `last_name`;");
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
