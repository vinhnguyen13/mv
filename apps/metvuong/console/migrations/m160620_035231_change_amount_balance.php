<?php

use yii\db\Schema;
use yii\db\Migration;

class m160620_035231_change_amount_balance extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ec_balance`
						  CHANGE `amount` `amount` INT(11) UNSIGNED NOT NULL,
						  CHANGE `amount_promotion` `amount_promotion` INT(11) UNSIGNED NOT NULL;");
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
