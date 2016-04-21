<?php

use yii\db\Migration;

class m160419_090942_add_user_report extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `user_review`
                            ADD COLUMN `ip` VARCHAR(40) NULL COMMENT '' AFTER `created_at`,
                            ADD COLUMN `is_report` TINYINT NULL COMMENT '' AFTER `ip`,
                            CHANGE COLUMN `type` `type` TINYINT(4) NULL DEFAULT NULL COMMENT ' Review: - 1: mua - 2: thue\n Report type is defined in UserReview list.\n' ;");
    }

    public function down()
    {
        $this->dropColumn('user_review', 'ip');
        $this->dropColumn('user_review', 'is_report');
    }

}
