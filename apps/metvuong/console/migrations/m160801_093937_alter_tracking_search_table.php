<?php

use yii\db\Migration;

class m160801_093937_alter_tracking_search_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `tracking_search`   
			ADD COLUMN `agent_js` VARCHAR(512) NULL AFTER `is_mobile`,
			ADD COLUMN `agent_php` VARCHAR(512) NULL AFTER `agent_js`,
			ADD COLUMN `real_referer` VARCHAR(512) NULL AFTER `agent_php`;");
    }

    public function down()
    {
        $this->execute("ALTER TABLE `metvuong`.`tracking_search`   
			DROP COLUMN `agent_js`, 
			DROP COLUMN `agent_php`, 
			DROP COLUMN `real_referer`;");
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
