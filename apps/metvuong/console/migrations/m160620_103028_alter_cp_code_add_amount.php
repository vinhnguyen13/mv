<?php

use yii\db\Migration;

class m160620_103028_alter_cp_code_add_amount extends Migration
{
    public function up()
    {
        $this->execute("ALTER TABLE `cp_code`
ADD COLUMN `amount` DECIMAL(13,2) NULL DEFAULT 0 COMMENT 'so % hoac so tien duoc giam' AFTER `updated_at`,
ADD COLUMN `amount_type` TINYINT NULL DEFAULT 1 COMMENT '1: Giam theo % \n2: Giam theo so tien' AFTER `amount`;");
    }

    public function down()
    {
        $this->dropColumn('cp_code', 'amount');
        $this->dropColumn('cp_code', 'amount_type');
    }


}
