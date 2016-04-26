<?php

use yii\db\Migration;

class m160426_070859_add_language_cms extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `cms_show`
                            ADD COLUMN `language_id` VARCHAR(5) NULL DEFAULT 'vi-VN' COMMENT '' AFTER `updated_by`;");
    }

    public function down()
    {
       $this->dropColumn('cms_show', 'language_id');
    }
}
