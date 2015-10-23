<?php

use yii\db\Schema;
use yii\db\Migration;

class m151007_070216_banner extends Migration
{
    public function up()
    {
        $this->createTable('banner', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'priority' => Schema::TYPE_SMALLINT,
            'image' => Schema::TYPE_STRING,
            'url' => Schema::TYPE_STRING,
            'description' => Schema::TYPE_STRING,
            'keyword' => Schema::TYPE_STRING,
            'alt_text' => Schema::TYPE_STRING,
            'additional_html' => Schema::TYPE_STRING,
            'created_at' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'updated_by' => Schema::TYPE_INTEGER . " DEFAULT '0'",
        ]);
    }

    public function down()
    {
        $this->dropTable('banner');
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
