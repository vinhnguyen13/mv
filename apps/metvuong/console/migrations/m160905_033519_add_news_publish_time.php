<?php

use yii\db\Migration;

class m160905_033519_add_news_publish_time extends Migration
{
    public function up()
    {
        $table = Yii::$app->db->schema->getTableSchema('cms_show');
        if (!isset($table->columns['publish_time'])) {
            $this->execute("ALTER TABLE `cms_show`  ADD COLUMN `publish_time` INT NULL COMMENT '' AFTER `hot_news`;
UPDATE `cms_show` SET publish_time = (case when created_at > 0 then created_at else UNIX_TIMESTAMP() end);"); // 1473094800 = 06 Sep 2016
        }
    }

    public function down()
    {
        $table = Yii::$app->db->schema->getTableSchema('cms_show');
        if (isset($table->columns['publish_time'])) {
            $this->execute("ALTER TABLE `cms_show`   DROP COLUMN `publish_time`;");
        }
    }
}
