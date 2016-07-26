<?php

use yii\db\Migration;

class m160726_032451_alter_tracking_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `tracking_search` ADD COLUMN `order_by` VARCHAR(32) NULL AFTER `size_max`;");
    }

    public function down()
    {
        $this->dropColumn('tracking_search', 'order_by');
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
