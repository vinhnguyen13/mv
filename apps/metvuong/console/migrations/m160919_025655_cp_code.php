<?php

use yii\db\Migration;

class m160919_025655_cp_code extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `cp_code` ADD COLUMN `use_repeat` TINYINT NULL DEFAULT 0 COMMENT '0: Ko su dung nhieu lan \n1: Su dung nhieu lan' AFTER `limit`;");
        $this->execute("ALTER TABLE `cp_history` DROP PRIMARY KEY;");
    }

    public function down()
    {
        $this->dropColumn('cp_code', 'use_repeat');
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
