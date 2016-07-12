<?php

use yii\db\Migration;

class m160712_024104_alter_slug extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_district` CHANGE `slug` `slug` VARCHAR(64) CHARSET utf8 COLLATE utf8_general_ci NULL;");
		$this->execute("ALTER TABLE `ad_ward` CHANGE `slug` `slug` VARCHAR(128) CHARSET utf8 COLLATE utf8_general_ci NULL;");
    }

    public function down()
    {
		$this->execute("ALTER TABLE `ad_district` CHANGE `slug` `slug` VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NULL;");
		$this->execute("ALTER TABLE `ad_ward` CHANGE `slug` `slug` VARCHAR(32) CHARSET utf8 COLLATE utf8_general_ci NULL;");
    }
}
