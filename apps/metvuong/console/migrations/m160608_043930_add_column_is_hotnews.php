<?php

use yii\db\Migration;
use yii\db\Schema;

class m160608_043930_add_column_is_hotnews extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `cms_show`
                            ADD COLUMN `hot_news` TINYINT NULL DEFAULT 0 COMMENT '' AFTER `language_id`; ");
    }

    public function down()
    {
        $this->dropColumn('cms_show', 'hot_news');
    }
}
