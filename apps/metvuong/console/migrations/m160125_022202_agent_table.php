<?php

use yii\db\Schema;
use yii\db\Migration;

class m160125_022202_agent_table extends Migration
{
    public function up()
    {
        $this->execute("DROP TABLE IF EXISTS `db_mv_tool`.`ad_agent`; CREATE TABLE `db_mv_tool`.`ad_agent` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(255) DEFAULT NULL,
              `address` varchar(500) DEFAULT NULL,
              `mobile` varchar(32) DEFAULT NULL,
              `phone` varchar(32) DEFAULT NULL,
              `fax` varchar(32) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `website` varchar(255) DEFAULT NULL,
              `tax_code` varchar(32) DEFAULT NULL,
              `rating` smallint(6) DEFAULT '0',
              `working_area` varchar(2000) DEFAULT NULL,
              `source` tinyint(2) DEFAULT NULL COMMENT '1: Batdongsan.com.vn\n2: Homefinder.vn\n3: Muaban.net',
              `type` tinyint(2) DEFAULT NULL COMMENT '1: cong ty \n2: ca nhan',
              `updated_at` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Moi gioi';");
    }

    public function down()
    {
        \vsoft\craw\models\AdAgent::getDb()->createCommand()->dropTable("ad_agent");
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
