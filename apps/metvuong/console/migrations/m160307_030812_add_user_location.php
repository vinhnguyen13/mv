<?php

use yii\db\Migration;

class m160307_030812_add_user_location extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `user_location` (
                              `user_id` INT NOT NULL COMMENT '',
                              `city_id` INT NULL COMMENT '',
                              `district_id` INT NULL COMMENT '',
                              `ward_id` INT NULL COMMENT '',
                              `street_id` INT NULL COMMENT '',
                              PRIMARY KEY (`user_id`)  COMMENT '');");
    }

    public function down()
    {
        $this->dropTable('user_location');
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
