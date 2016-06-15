<?php

use yii\db\Migration;

/**
 * Handles the creation for table `mark_email`.
 */
class m160615_072331_create_mark_email extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("CREATE TABLE `mark_email` (
  `email` VARCHAR(255) NOT NULL COMMENT '',
  `type` TINYINT NULL COMMENT 'Send by Beta Type, Opened Type',
  `count` INT NULL DEFAULT 0 COMMENT '',
  `status` TINYINT NULL COMMENT '',
  `send_time` INT NULL COMMENT '',
  PRIMARY KEY (`email`)  COMMENT '')
COMMENT = 'Mark emails has sent in Mail Marketing';");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mark_email');
    }
}
