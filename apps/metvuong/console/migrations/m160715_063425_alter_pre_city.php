<?php

use yii\db\Migration;
use vsoft\ad\models\AdCity;

class m160715_063425_alter_pre_city extends Migration
{
    public function up()
    {
		$cities = AdCity::find()->all();
		
		foreach ($cities as $city) {
			$city->pre = mb_convert_case($city->pre, MB_CASE_TITLE, "UTF-8");
			$city->save(false);
		}
    }

    public function down()
    {
    	$cities = AdCity::find()->all();
    	
    	foreach ($cities as $city) {
    		$city->pre = mb_strtolower($city->pre, 'UTF-8');
    		$city->save(false);
    	}
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
