<?php

use yii\db\Schema;
use yii\db\Migration;

class m151204_024254_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_images` ADD COLUMN `user_id` INT NOT NULL AFTER `id`;");
		$this->execute("ALTER TABLE `ad_product` DROP COLUMN `title`;");
    }

    public function down()
    {
        $this->dropColumn('ad_images', 'user_id');
        $this->execute("ALTER TABLE `ad_product` ADD COLUMN `title` VARCHAR(255) NOT NULL AFTER `street_id`;");
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
