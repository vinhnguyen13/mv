<?php

use yii\db\Migration;

class m160823_104313_ad_product_saved_change extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_product_saved`
        ADD COLUMN `status` TINYINT NULL DEFAULT 0 COMMENT '0: un-favorite % \n1: favorite;'");
    }

    public function down()
    {
        $this->dropColumn('ad_product_saved', 'status');
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
