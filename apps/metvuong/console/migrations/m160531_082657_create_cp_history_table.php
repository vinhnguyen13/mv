<?php

use yii\db\Migration;

/**
 * Handles the creation for table `cp_history_table`.
 */
class m160531_082657_create_cp_history_table extends Migration
{
    private $table = 'cp_history';
    /**
     * @inheritdoc
     */
    public function up()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(!is_object($table_check)) {
            $this->execute("CREATE TABLE `cp_history` (
                              `user_id` INT NOT NULL COMMENT '',
                              `cp_code_id` INT NOT NULL COMMENT '',
                              `cp_event_id` INT NULL COMMENT '',
                              `created_at` INT NULL COMMENT '',
                              PRIMARY KEY (`user_id`, `cp_code_id`, `cp_event_id`)  COMMENT '');");
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
