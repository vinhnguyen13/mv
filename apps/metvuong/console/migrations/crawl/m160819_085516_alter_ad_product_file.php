<?php

use yii\db\Migration;

class m160819_085516_alter_ad_product_file extends Migration
{
    public function up()
    {
        $schema = \console\models\Helpers::getDbTool();
        $table = Yii::$app->dbCraw->schema->getTableSchema('ad_product_file');
        if (!isset($table->columns['description'])) {
            $this->execute("ALTER TABLE {$schema}.`ad_product_file` ADD COLUMN `description` VARCHAR(500) NULL COMMENT '' AFTER `updated_at`;");
        } else {
            print_r(" description exists ");
        }
    }

    public function down()
    {
        $schema = \console\models\Helpers::getDbTool();
        $this->execute("ALTER TABLE {$schema}.`ad_product_file`
DROP COLUMN `description`;");
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
