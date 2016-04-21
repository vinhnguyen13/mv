<?php

use yii\db\Migration;

class m160421_032319_report_type_table extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `report_type` (
                          `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
                          `name` VARCHAR(255) NOT NULL COMMENT '',
                          `is_user` TINYINT NULL  DEFAULT '0' COMMENT '1 - user \n0 - product',
                          `created_at` INT NULL COMMENT '',
                          `created_by` INT NULL COMMENT '',
                          PRIMARY KEY (`id`)  COMMENT '')
                        COMMENT = 'Cac loai report bao gom report product va user';");
        $this->execute("INSERT INTO `report_type` (`name`, `created_by`) VALUES ('It is spam', '1');
                        INSERT INTO `report_type` (`name`, `created_by`) VALUES ('It is inappropriate', '1');
                        INSERT INTO `report_type` (`name`, `created_by`) VALUES ('It insults or attacks someone based on their religion, ethnicity or sexual orientation', '1');
                        INSERT INTO `report_type` (`name`, `created_by`) VALUES ('It describes buying or selling drugs, guns or regulated products', '1');
                        INSERT INTO `report_type` (`name`, `is_user`, `created_by`) VALUES ('It is spam', '1', '1');
                        INSERT INTO `report_type` (`name`, `is_user`, `created_by`) VALUES ('It is inappropriate', '1', '1');
                        INSERT INTO `report_type` (`name`, `is_user`, `created_by`) VALUES ('It insults or attacks someone based on their religion, ethnicity or sexual orientation', '1', '1');
                        INSERT INTO `report_type` (`name`, `is_user`, `created_by`) VALUES ('It describes buying or selling drugs, guns or regulated users', '1', '1');
                        ");
    }

    public function down()
    {
        $this->dropTable('report_type');
    }
}
