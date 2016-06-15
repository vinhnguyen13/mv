<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ec_package`.
 */
class m160606_105130_create_ec_package extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("CREATE TABLE `ec_package` (
                          `id` SMALLINT NOT NULL AUTO_INCREMENT COMMENT '',
                          `title` VARCHAR(255) NULL COMMENT '',
                          `description` VARCHAR(500) NULL COMMENT '',
                          `status` TINYINT NULL DEFAULT 1 COMMENT '',
                          `created_at` INT NULL COMMENT '',
                          `updated_at` INT NULL COMMENT '',
                          PRIMARY KEY (`id`)  COMMENT '')
                        COMMENT = 'Package amount of Metvuong ';");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ec_package');
    }
}
