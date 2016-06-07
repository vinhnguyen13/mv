<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ec_charge`.
 */
class m160606_104144_create_ec_charge extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("CREATE TABLE `ec_charge` (
                          `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
                          `charge` DECIMAL(13,2) NULL DEFAULT 0 COMMENT '',
                          `type` TINYINT NULL DEFAULT 1 COMMENT '1: amount - 2: percent',
                          `description` VARCHAR(255) NULL COMMENT '',
                          `status` TINYINT NULL DEFAULT 1 COMMENT '',
                          `created_at` INT NULL COMMENT '',
                          PRIMARY KEY (`id`)  COMMENT '')
                        COMMENT = 'payment by amount or percent';
                        ");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ec_charge');
    }
}
