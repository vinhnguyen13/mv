<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ec_payment_method`.
 */
class m160606_104925_create_ec_payment_method extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("CREATE TABLE `ec_payment_method` (
                          `id` SMALLINT NOT NULL AUTO_INCREMENT COMMENT '',
                          `title` VARCHAR(255) NULL COMMENT '',
                          `description` VARCHAR(500) NULL COMMENT '',
                          `status` TINYINT NULL DEFAULT 1 COMMENT '',
                          `created_at` INT NULL COMMENT '',
                          PRIMARY KEY (`id`)  COMMENT '')
                        COMMENT = 'Payment method: Nganluong, Baokim...';");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ec_payment_method');
    }
}
