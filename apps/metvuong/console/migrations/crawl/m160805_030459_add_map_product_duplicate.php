<?php

use yii\db\Migration;

class m160805_030459_add_map_product_duplicate extends Migration
{
    public function up()
    {
        $schema = \console\models\Helpers::getDbTool();
        $this->execute("CREATE TABLE {$schema}.`map_product_duplicate` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `product_main_id` int(11) NOT NULL,
                          `duplicate_id` int(11) DEFAULT NULL COMMENT 'Product Main ID duplicate',
                          `tool_id` int(11) DEFAULT NULL COMMENT 'Product Tool ID of duplicate',
                          `is_duplicate` tinyint(4) DEFAULT '0' COMMENT '1: duplicate\n0: not found DB Tool',
                          `created_at` int(11) DEFAULT NULL,
                          PRIMARY KEY (`id`) );
                          ");
    }

    public function down()
    {
        $schema = \console\models\Helpers::getDbTool();
        $this->execute("DROP TABLE {$schema}.`map_product_duplicate`;");
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
