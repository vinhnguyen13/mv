<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

class m160108_025340_alter_ads_table extends Migration
{
    public function up()
    {
    	$this->execute("ALTER TABLE `ad_ward` ADD COLUMN `geometry` TEXT NULL AFTER `name`;");
    	$this->execute("ALTER TABLE `ad_district` ADD COLUMN `geometry` TEXT NULL AFTER `name`;");
    	
    	$connection = \Yii::$app->db;
    	$city = $connection->createCommand("SELECT `id` FROM `ad_city` WHERE `code` = 'SG'")->queryOne();
    	$cityId = $city['id'];
    	
    	$dictricts = $connection->createCommand("SELECT * FROM `ad_district` WHERE `city_id` = $cityId")->queryAll();
    	foreach ($dictricts as &$district) {
    		$district['key'] = strtolower(str_replace(' ', '_', $this->convertKey(trim($district['pre'] . ' ' . $district['name']))));
    	}
    	$dictricts = ArrayHelper::index($dictricts, 'key');
    	
    	$data = include 'bounds.php';
    	$data = json_decode($data, true);
    	$bounds = $data['bounds'];
    	$crawWards = [];
    	$crawDistricts = [];
    	
    	foreach ($bounds as $bound) {
    		if(isset($bound['parent_key'])) {
    			if(!isset($crawWards[$bound['parent_key']])) {
    				$crawWards[$bound['parent_key']] = [];
    			}
    			$crawWards[$bound['parent_key']][] = $bound;
    		} else {
    			$crawDistricts[$bound['key']] = $bound;
    		}
    	}
    	
    	$mapIncorectName = [
			'xa_qui_duc' => [
				'name' => 'Quy Đức',
				'pre' => 'Xã',
    		],
    		'thi_tran_can_thanh' => [
				'name' => 'Cần Thạnh ',
				'pre'	=> 'Phường',
    		],
    		'xa_thai_mi' => [
				'name' => 'Thái Mỹ',
				'pre' => 'Xã'
    		],
    		'thi_tran_hoc_mon' => [
				'name' => ' Hóc Môn',
				'pre' => 'Phường'
    		],
    		'xa_ba_diem' => [
				'name' => 'Bà Điểm',
				'pre' => 'Phường'
    		],
    		'xa_dong_thanh' => [
    			'name' => ' Đông Thạnh',
    			'pre' => 'Xã'
    		],
    		'xa_hiep_phuoc' => [
				'name' => 'Hiệp Phước',
				'pre' => 'Phường',
    		],
    		'phuong_thanh_my_loi' => [
				'name' => ' Thạnh Mỹ Lợi',
				'pre' => 'Phường'
    		],
    		'phuongtang_nhon_phu_a' => [
				'name' => 'Tăng Nhơn Phú A',
				'pre' => 'Phường'
    		]
    	];
    	
    	$total = 0;
    	foreach ($dictricts as $dictrict) {
    		if(isset($crawDistricts[$dictrict['key']])) {
    			$this->execute("UPDATE `ad_district` SET `geometry` = '" . json_encode($crawDistricts[$dictrict['key']]['lat_lon']) . "' WHERE `id` = {$dictrict['id']};");
    		}
    		
    		if(isset($crawWards[$dictrict['key']])) {
    			foreach ($crawWards[$dictrict['key']] as $crawWard) {
    				if(isset($mapIncorectName[$crawWard['key']])) {
    					$splitName = $mapIncorectName[$crawWard['key']];
    				}  else {
    					$splitName = $this->splitName($crawWard['name']);
    				}
    				
					$this->execute("UPDATE `ad_ward` SET `geometry` = '" . json_encode($crawWard['lat_lon']) . "' WHERE `district_id` = {$dictrict['id']} AND `name` = '{$splitName['name']}' AND `pre` = '{$splitName['pre']}';");
				
					$rrrr = $connection->createCommand("SELECT * FROM `ad_ward` WHERE `district_id` = {$dictrict['id']} AND `name` = '{$splitName['name']}' AND `pre` = '{$splitName['pre']}';")->queryOne();
    				if(!$rrrr) {
    					echo 'ddddd';
    					var_dump($crawWard);
    				}
    			}
    		}
    	}
    }
    
    function convertKey($str){
    	$unicode = array(
			'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
    		'd'=>'đ',
    		'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
	    	'i'=>'í|ì|ỉ|ĩ|ị',
    		'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
    		'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
    		'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
    		'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
    		'D'=>'Đ',
    		'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
    		'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
    		'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
    		'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
    		'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    	);
    
    	foreach($unicode as $nonUnicode => $uni){
    		$str = preg_replace("/($uni)/i", $nonUnicode, $str);
    	}
    	return $str;
    }
    
    private function splitName($name) {
    	$arrayPre = ['Quận', 'Huyện', 'Phường', 'Xã', 'Thị Trấn'];
    	$return = [];
    	foreach ($arrayPre as $pre) {
    		if(StringHelper::startsWith($name, $pre)) {
    			$return['pre'] = $pre;
    			$return['name'] = str_replace("$pre ", "", $name);
    			break;
    		}
    	}
    	return $return;
    }

    public function down()
    {
        $this->execute("ALTER TABLE `metvuong`.`ad_district` DROP COLUMN `geometry`;");
        $this->execute("ALTER TABLE `metvuong`.`ad_ward` DROP COLUMN `geometry`;");
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
