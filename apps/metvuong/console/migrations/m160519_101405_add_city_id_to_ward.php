<?php

use yii\db\Schema;
use yii\db\Migration;
use vsoft\ad\models\AdCity;
use vsoft\ad\models\AdDistrict;

class m160519_101405_add_city_id_to_ward extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_ward` ADD COLUMN `city_id` INT(11) NOT NULL AFTER `id`;");
		
		$cities = AdCity::find()->all();
		
		$connection = \Yii::$app->db;
		
		foreach ($cities as $city) {
			echo $city->id;
			$districts = AdDistrict::find()->where('city_id =' . $city->id)->all();
			
			foreach ($districts as $district) {
				$connection->createCommand()->update('ad_ward', ['city_id' => $city->id], 'district_id = ' . $district->id)->execute();
			}
		}
    }

    public function down()
    {
        $this->dropColumn('ad_ward', 'city_id');
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
