<?php

use yii\db\Schema;
use yii\db\Migration;

class m151028_031541_cms_catalog extends Migration
{
    public function up()
    {
        $this->addColumn('cms_catalog','slug', 'varchar(255) NOT NULL after title');
    }

    public function down()
    {
        $this->dropColumn('cms_catalog','slug');
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
