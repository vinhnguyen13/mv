<?php

use yii\db\Migration;

class m160325_031000_alter_investor_logo extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_investor`
                          CHANGE COLUMN `logo` `logo` VARCHAR(255) NULL COMMENT '' ;");
    }

    public function down()
    {

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
