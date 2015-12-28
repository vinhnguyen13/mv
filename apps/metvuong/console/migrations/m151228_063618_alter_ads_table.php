<?php

use yii\db\Schema;
use yii\db\Migration;

class m151228_063618_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_images` CHANGE `user_id` `user_id` INT(11) NULL;");
    }

    public function down()
    {
    	$this->execute("ALTER TABLE `ad_images` CHANGE `user_id` `user_id` INT(11) NOT NULL;");
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
