<?php

use yii\db\Migration;

class m160718_093244_alter_is_null_homeno extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_product`
CHANGE COLUMN `home_no` `home_no` VARCHAR(32) NULL COMMENT '' ;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_product`
CHANGE COLUMN `home_no` `home_no` VARCHAR(32) NOT NULL COMMENT '' ;");
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
