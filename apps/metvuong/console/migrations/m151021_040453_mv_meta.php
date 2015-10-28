<?php

use yii\db\Schema;
use yii\db\Migration;

class m151021_040453_mv_meta extends Migration
{
    private $table = 'mv_meta';
    public function up()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(!is_object($table_check)) {
            $this->createTable($this->table, [
                'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'url' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT "/"',
                'metadata' => Schema::TYPE_TEXT,
            ]);
            $this->createIndex('idx_url', $this->table, 'url', true);
        }
    }

    public function down()
    {
        $table_check = Yii::$app->db->schema->getTableSchema($this->table);
        if(is_object($table_check)) {
            $this->dropTable($this->table);
        }
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
