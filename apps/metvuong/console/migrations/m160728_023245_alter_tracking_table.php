<?php

use yii\db\Migration;

class m160728_023245_alter_tracking_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `tracking_search`   
			ADD COLUMN `referer` VARCHAR(255) NULL AFTER `order_by`,
			ADD COLUMN `is_mobile` TINYINT(1) NULL AFTER `referer`;");
    }

    public function down()
    {
       $this->execute("ALTER TABLE `tracking_search`   
		  DROP COLUMN `referer`, 
		  DROP COLUMN `is_mobile`;");
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
