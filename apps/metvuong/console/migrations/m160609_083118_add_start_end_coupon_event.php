<?php

use yii\db\Migration;

class m160609_083118_add_start_end_coupon_event extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `cp_event`
                            ADD COLUMN `start_date` INT NULL COMMENT '' AFTER `created_by`,
                            ADD COLUMN `end_date` INT NULL COMMENT '' AFTER `start_date`;");
    }

    public function down()
    {
        $this->dropColumn('cp_event', 'start_date');
        $this->dropColumn('cp_event', 'end_date');
    }
}
