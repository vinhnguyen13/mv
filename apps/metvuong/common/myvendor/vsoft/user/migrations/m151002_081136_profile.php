<?php

use yii\db\Schema;
use yii\db\Migration;

class m151002_081136_profile extends Migration
{
    public function up()
    {
        $this->addColumn('{{%profile}}', 'avatar', Schema::TYPE_STRING . '(255) NOT NULL AFTER `bio`');
    }

    public function down()
    {
        $this->dropColumn('{{%profile}}', 'avatar');
        echo "m151002_081136_profile cannot be reverted.\n";
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
