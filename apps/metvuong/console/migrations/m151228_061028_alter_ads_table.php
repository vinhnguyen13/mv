<?php

use yii\db\Schema;
use yii\db\Migration;

class m151228_061028_alter_ads_table extends Migration
{
    public function up()
    {
    	$this->execute("insert  into `ad_category`(`id`,`name`,`apply_to_type`,`order`,`status`,`template`,`limit_area`) values (6,'căn hộ chung cư',1,0,1,1,200),(7,'nhà riêng',3,1,1,2,200),(8,'nhà biệt thự, liền kề',3,2,1,2,200),(9,'nhà mặt phố',3,3,1,2,200),(10,'đất nền dự án',1,4,1,2,NULL),(11,'đất',1,5,0,1,NULL),(12,'trang trại, khu nghỉ dưỡng',1,6,0,1,NULL),(13,'kho, nhà xưởng',1,7,0,1,NULL),(14,'loại bất động sản khác',1,12,0,1,NULL),(15,'nhà trọ, phòng trọ',2,8,0,1,NULL),(16,'văn phòng',2,9,0,1,NULL),(17,'cửa hàng, ki ốt',2,10,0,1,NULL),(18,'kho, nhà xưởng, đất',2,11,0,1,NULL);");

    }

    public function down()
    {
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
