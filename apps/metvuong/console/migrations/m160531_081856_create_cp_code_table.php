<?php

use yii\db\Migration;

/**
 * Handles the creation for table `cp_code_table`.
 */
class m160531_081856_create_cp_code_table extends Migration
{
    private $table = 'cp_code';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(!is_object($table_check)) {
            $this->execute("CREATE TABLE `cp_code` (
                          `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
                          `code` VARCHAR(32) NULL COMMENT '',
                          `cp_event_id` INT NULL COMMENT '',
                          `status` TINYINT NULL DEFAULT 1 COMMENT '1: active\n0: not active',
                          `count` INT NULL DEFAULT 0 COMMENT 'so lan su dung code',
                          `type` TINYINT NULL DEFAULT 2 COMMENT '1: su dung 1 lan\n2 or > 1: su dung nhieu lan',
                          `created_at` INT NULL COMMENT '',
                          `updated_at` INT NULL COMMENT '',
                          PRIMARY KEY (`id`)  COMMENT '',
                          UNIQUE KEY `code_UNIQUE` (`code`),
                          KEY `cp_event_idx` (`cp_event_id`),
                          CONSTRAINT `cp_code_event` FOREIGN KEY (`cp_event_id`) REFERENCES `cp_event` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION);");
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
