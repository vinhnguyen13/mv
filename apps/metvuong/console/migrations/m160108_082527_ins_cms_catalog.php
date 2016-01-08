<?php

use yii\db\Expression;
use yii\db\Schema;
use yii\db\Migration;

class m160108_082527_ins_cms_catalog extends Migration
{
    public function up()
    {
        $this->insert('cms_catalog', [
            'id' => 20,
            'parent_id' => 0,
            'title' => 'Homepage',
            'created_at' => new Expression('UNIX_TIMESTAMP()'),
            'updated_at' => new Expression('UNIX_TIMESTAMP()')
        ]);

    }

    public function down()
    {
        $this->delete('cms_catalog', ['id' => 20]);
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
