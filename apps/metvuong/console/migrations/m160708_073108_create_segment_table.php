<?php

use yii\db\Migration;

/**
 * Handles the creation for table `segment_table`.
 */
class m160708_073108_create_segment_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->execute("CREATE TABLE `slug_search`(  
		  `id` INT NOT NULL AUTO_INCREMENT,
		  `slug` VARCHAR(255) NOT NULL,
		  `table` VARCHAR(32) NOT NULL,
		  `value` INT NOT NULL,
		  PRIMARY KEY (`id`),
          KEY (`slug`)
		);");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('slug_search');
    }
}
