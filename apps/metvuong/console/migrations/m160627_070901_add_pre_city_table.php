<?php

use yii\db\Schema;
use yii\db\Migration;

class m160627_070901_add_pre_city_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_city` ADD COLUMN `pre` VARCHAR(32) NULL AFTER `status`");
		$this->execute("UPDATE `ad_city` SET `pre` = 'tỉnh' WHERE `name` NOT IN ('Cần Thơ', 'Đà Nẵng', 'Hải Phòng', 'Hà Nội', 'Hồ Chí Minh')");
		$this->execute("UPDATE `ad_city` SET `pre` = 'thành phố' WHERE `name` IN ('Cần Thơ', 'Đà Nẵng', 'Hải Phòng', 'Hà Nội', 'Hồ Chí Minh')");
    }

    public function down()
    {
        $this->dropColumn('ad_city', 'pre');
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
