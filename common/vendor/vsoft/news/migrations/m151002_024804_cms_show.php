<?php

use yii\db\Schema;
use yii\db\Migration;

class m151002_024804_cms_show extends Migration
{
    public function up()
    {
        $this->addColumn('{{%cms_show}}', 'slug', Schema::TYPE_STRING . '(255) NOT NULL AFTER `title`');
        $this->addColumn('{{%cms_show}}', 'updated_by', Schema::TYPE_INTEGER . '(11) DEFAULT 0');
        $this->addColumn('{{%cms_show}}', 'updated_by', Schema::TYPE_INTEGER . '(11) DEFAULT 0');
    }

    public function down()
    {
        echo "m151002_024804_cms_show cannot be reverted.\n";

        return false;
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
