<?php

use yii\db\Schema;
use yii\db\Migration;

class m151021_040453_lc_meta extends Migration
{
    public function up()
    {
        $this->createTable('{{%lc_meta}}', [
            'id'      => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'url' => Schema::TYPE_STRING . '(255) NOT NULL DEFAULT "/"',
            'metadata'  => Schema::TYPE_TEXT ,
        ]);
        $this->createIndex('idx_url', '{{%lc_meta}}', 'url', true);
    }

    public function down()
    {
        $this->dropTable('{{%lc_meta}}');
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
