<?php

use yii\db\Migration;

class m160826_025714_table_tracking_product_update extends Migration
{
    public function up()
    {
        $schema = \console\models\Helpers::getDbTool();
        $this->execute("CREATE TABLE IF NOT EXISTS {$schema}.`tracking_product_update` (
                          `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
                          `file` VARCHAR(32) NULL COMMENT '',
                          `product_tool_id` INT NOT NULL COMMENT '',
                          `new_price` BIGINT(20) NULL COMMENT '',
                          `new_area` FLOAT NULL COMMENT '',
                          `new_room_no` INT NULL COMMENT '',
                          `new_toilet_no` INT NULL COMMENT '',
                          `description` VARCHAR(255) NULL COMMENT '',
                          `status` TINYINT NULL DEFAULT 1 COMMENT '1 : link con hoat dong\n0 : link khong hoat dong hoac bi xoa ',
                          `created_at` INT NULL COMMENT '',
                          PRIMARY KEY (`id`)  COMMENT '')
                        COMMENT = 'Track lai product trong ngay';");
    }

    public function down()
    {
        $schema = \console\models\Helpers::getDbTool();
        $this->execute("DROP TABLE IF EXISTS {$schema}.`tracking_product_update`;");
    }
}
