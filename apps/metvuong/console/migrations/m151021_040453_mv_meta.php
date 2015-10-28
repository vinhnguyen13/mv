<?php

use yii\db\Schema;
use yii\db\Migration;

class m151021_040453_mv_meta extends Migration
{
    public function up()
    {
        $this->createTable('{{%mv_meta}}', [
            'id'      => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT "/"',
            'metadata'  => Schema::TYPE_TEXT ,
        ]);
        $this->createIndex('idx_url', '{{%mv_meta}}', 'url', true);
    }

    public function down()
    {
        $this->dropTable('{{%mv_meta}}');
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
