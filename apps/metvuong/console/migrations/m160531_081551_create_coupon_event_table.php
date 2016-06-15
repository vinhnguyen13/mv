<?php

use yii\db\Migration;

/**
 * Handles the creation for table `coupon_event_table`.
 */
class m160531_081551_create_coupon_event_table extends Migration
{
    private $table = 'cp_event';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(!is_object($table_check)) {
            $this->execute("CREATE TABLE `cp_event` (
                          `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
                          `name` VARCHAR(255) NULL COMMENT '',
                          `description` VARCHAR(3200) NULL COMMENT '',
                          `status` TINYINT NULL DEFAULT 1 COMMENT '1: active\n0: not active',
                          `created_at` INT NULL COMMENT '',
                          `created_by` INT NULL COMMENT '',
                          PRIMARY KEY (`id`)  COMMENT '');
                        ");
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->table);
    }
}
