<?php

use yii\db\Schema;
use yii\db\Migration;

class m160202_061601_alter_profile extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `profile`
                            ADD COLUMN `about` VARCHAR(1022) NULL COMMENT '' AFTER `address`,
                            ADD COLUMN `activity` VARCHAR(1022) NULL COMMENT '' AFTER `about`,
                            ADD COLUMN `experience` VARCHAR(1022) NULL COMMENT '' AFTER `activity`,
                            ADD COLUMN `slug` VARCHAR(255) NULL COMMENT '' AFTER `experience`;");

    }

    public function down()
    {
        $this->dropColumn("profile", "slug");
        $this->dropColumn("profile", "about");
        $this->dropColumn("profile", "activity");
        $this->dropColumn("profile", "experience");
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
