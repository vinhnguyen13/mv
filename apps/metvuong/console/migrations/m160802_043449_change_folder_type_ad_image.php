<?php

use yii\db\Migration;

class m160802_043449_change_folder_type_ad_image extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_images` CHANGE `folder` `folder` VARCHAR(255) NOT NULL;");
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
