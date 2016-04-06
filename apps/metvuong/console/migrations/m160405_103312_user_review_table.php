<?php

use yii\db\Migration;

class m160405_103312_user_review_table extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `user_review` (
                          `user_id` INT NOT NULL COMMENT 'user login to send review',
                          `review_id` INT NOT NULL COMMENT 'user_id of profile view',
                          `name` varchar(255) DEFAULT NULL,
                          `username` varchar(255) NOT NULL,
                          `rating` SMALLINT NULL DEFAULT 0 COMMENT '',
                          `type` TINYINT NULL COMMENT '1: mua - 2: thue',
                          `description` TEXT NULL COMMENT '',
                          `created_at` INT NULL COMMENT '',
                           PRIMARY KEY (`review_id`, `user_id`)  COMMENT '');");

        $this->execute("ALTER TABLE `profile`
                            ADD COLUMN `rating_point` FLOAT NULL DEFAULT 0 COMMENT '' AFTER `address`,
                            ADD COLUMN `rating_no` INT NULL DEFAULT 0 COMMENT '' AFTER `rating_point`;
                            ");
    }

    public function down()
    {
        $this->dropTable("user_review");
        $this->dropColumn("profile", "rating_point");
        $this->dropColumn("profile", "rating_no");
    }
}
