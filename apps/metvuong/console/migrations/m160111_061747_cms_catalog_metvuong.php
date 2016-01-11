<?php

use yii\db\Expression;
use yii\db\Schema;
use yii\db\Migration;

class m160111_061747_cms_catalog_metvuong extends Migration
{
    public function up()
    {
        $this->update('cms_catalog', [
            'slug' => 'home-page'
        ], ['id' => 20]);

        $this->delete('cms_catalog', ['title' => 'Metvuong']);
        $this->delete('cms_catalog', ['id' => 21]);
        $this->insert('cms_catalog', [
            'id' => 21,
            'parent_id' => 2,
            'title' => 'Metvuong',
            'slug' => 'met-vuong',
            'created_at' => new Expression('UNIX_TIMESTAMP()'),
            'updated_at' => new Expression('UNIX_TIMESTAMP()')
        ]);
    }

    public function down()
    {
        $this->delete('cms_catalog', ['id' => 21]);
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
