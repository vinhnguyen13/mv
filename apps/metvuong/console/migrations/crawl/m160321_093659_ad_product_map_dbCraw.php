<?php

use yii\db\Migration;

class m160321_093659_ad_product_map_dbCraw extends Migration
{
    public function up()
    {
//        if (count(Yii::$app->dbCraw) > 0) {
//            $this->execute("CREATE TABLE `db_mv_tool`.`ad_product_tool_map` (
//                                  `product_main_id` int(11) DEFAULT NULL,
//                                  `product_tool_id` int(11) DEFAULT NULL
//                                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
//            $this->execute("ALTER TABLE `db_mv_tool`.`ad_product_tool_map`
//                                ADD UNIQUE INDEX `uq_product_tool_id` (`product_tool_id` ASC)  COMMENT '';");
//        }
    }

    public function down()
    {
//        $this->execute("DROP TABLE `db_mv_tool`.`ad_product_tool_map`;");
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
