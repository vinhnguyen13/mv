<?php

use yii\db\Schema;
use yii\db\Migration;

class m151202_040744_update_ads_table_unicode extends Migration
{
    public function up()
    {
		$this->replace('ad_city');
		$this->replace('ad_district');
		$this->replace('ad_ward');
		$this->replace('ad_street');
    }

    public function down()
    {
    	
    }
    
    public function replace($table, $up = true) {
    	$map = [ 'đ' => 'đ', 'á' => 'á', 'à' => 'à', 'ả' => 'ả', 'ã' => 'ã', 'ạ' => 'ạ', 'ấ' => 'ấ', 'ầ' => 'ầ', 'ẩ' => 'ẩ', 'ẫ' => 'ẫ', 'ậ' => 'ậ', 'ắ' => 'ắ', 'ằ' => 'ằ', 'ẳ' => 'ẳ', 'ẵ' => 'ẵ', 'ặ' => 'ặ', 'ó' => 'ó', 'ò' => 'ò', 'ỏ' => 'ỏ', 'õ' => 'õ', 'ọ' => 'ọ', 'ố' => 'ố', 'ồ' => 'ồ', 'ổ' => 'ổ', 'ỗ' => 'ỗ', 'ộ' => 'ộ', 'ớ' => 'ớ', 'ờ' => 'ờ', 'ở' => 'ở', 'ỡ' => 'ỡ', 'ợ' => 'ợ', 'í' => 'í', 'ì' => 'ì', 'ỉ' => 'ỉ', 'ĩ' => 'ĩ', 'ị' => 'ị', 'é' => 'é', 'è' => 'è', 'ẻ' => 'ẻ', 'ẽ' => 'ẽ', 'ẹ' => 'ẹ', 'ế' => 'ế', 'ề' => 'ề', 'ể' => 'ể', 'ễ' => 'ễ', 'ệ' => 'ệ', 'ú' => 'ú', 'ù' => 'ù', 'ủ' => 'ủ', 'ũ' => 'ũ', 'ụ' => 'ụ', 'ứ' => 'ứ', 'ừ' => 'ừ', 'ử' => 'ử', 'ữ' => 'ữ', 'ự' => 'ự', 'ý' => 'ý', 'ỳ' => 'ỳ', 'ỷ' => 'ỷ', 'ỹ' => 'ỹ', 'ỵ' => 'ỵ'];
    	
    	if($up) {
    		$search = array_keys($map);
    		$replace = array_values($map);
    	} else {
    		$replace = array_keys($map);
    		$search = array_values($map);
    	}
    	
    	$connection = \Yii::$app->db;
    	$command = $connection->createCommand("SELECT * FROM `$table`");
    	$records = $command->queryAll();
    	
    	foreach ($records as $record) {
    		$name = str_replace($search, $replace, $record['name']);
    		
    		if($name != $record['name']) {
    			$this->execute("UPDATE `$table` SET `name` = '$name' WHERE `id` = {$record['id']};");
    		}
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
