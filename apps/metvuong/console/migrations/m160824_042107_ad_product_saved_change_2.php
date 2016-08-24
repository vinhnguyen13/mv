<?php

use yii\db\Migration;

class m160824_042107_ad_product_saved_change_2 extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `ad_product_saved`
        ADD COLUMN `updated_at` int(11) DEFAULT '0';");
    }

    public function down()
    {
        $this->dropColumn('ad_product_saved', 'updated_at');
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
