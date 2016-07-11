<?php

use yii\db\Migration;

class m160711_030618_add_slug_street extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_street` ADD COLUMN `slug` VARCHAR(255) NULL AFTER `status`;");
    }

    public function down()
    {
        $this->dropColumn('ad_street', 'slug');
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
