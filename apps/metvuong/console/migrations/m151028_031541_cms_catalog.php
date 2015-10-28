<?php

use yii\db\Schema;
use yii\db\Migration;

class m151028_031541_cms_catalog extends Migration
{
    private $table = 'cms_catalog';
    private $column = 'slug';
    public function up()
    {
        $table_to_check = Yii::$app->db->schema->getTableSchema($this->table);
        if (! isset( $table_to_check->columns[$this->column] )) {
            $this->addColumn($this->table, $this->column, 'varchar(255) NOT NULL after title');
        }
    }

    public function down()
    {
        $table_to_check = Yii::$app->db->schema->getTableSchema($this->table);
        if (isset( $table_to_check->columns[$this->column] )) {
            $this->dropColumn($this->table, $this->column);
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
