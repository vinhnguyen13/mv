<?php
namespace console\controllers;

use yii\console\Controller;

class ProjectController extends Controller {
	public function actionUpdate() {
		$projects = \Yii::$app->db->createCommand("SELECT id, location, district_id FROM `ad_building_project`")->queryAll();
			
		$wards = \Yii::$app->db->createCommand("SELECT id, name, district_id FROM `ad_ward`")->queryAll();
		$streets = \Yii::$app->db->createCommand("SELECT id, name, district_id FROM `ad_street`")->queryAll();
			
		foreach ($projects as $project) {
			$parseLocation = $this->parseLocation($project);
		
			if(isset($parseLocation['street']) && !isset($parseLocation['ward']) && $parseLocation['remainSplit']) {
				$parseLocation['ward'] = $parseLocation['remainSplit'][0];
			}
			
			if(isset($parseLocation['homeNo']) && !isset($parseLocation['street']) && $parseLocation['remainSplit']) {
				$parseLocation['street'] = $parseLocation['remainSplit'][0];
			}
		
			$update = [];
		
			if(isset($parseLocation['homeNo'])) {
				$update['home_no'] = $parseLocation['homeNo'];
			}
		
			if(isset($parseLocation['street'])) {
				if(stripos($parseLocation['street'], ' và ')) {
					$parseLocation['street'] = explode(' và ', $parseLocation['street']);
					$parseLocation['street'] = $parseLocation['street'][0];
				}
				if(stripos($parseLocation['street'], ' – ')) {
					$parseLocation['street'] = explode(' – ', $parseLocation['street']);
					$parseLocation['street'] = $parseLocation['street'][0];
				}
				if(stripos($parseLocation['street'], ' - ')) {
					$parseLocation['street'] = explode(' - ', $parseLocation['street']);
					$parseLocation['street'] = $parseLocation['street'][0];
				}
		
				$parseLocation['street'] = str_ireplace([' nối dài', ' kéo dài'], '', $parseLocation['street']);
		
				foreach ($streets as $street) {
					if(strcasecmp($parseLocation['street'], $street['name']) === 0 && $street['district_id'] == $project['district_id']) {
						$update['street_id'] = $street['id'];
						break;
					} else if($this->slug($parseLocation['street']) == $this->slug($street['name']) && $street['district_id'] == $project['district_id']) {
						$update['street_id'] = $street['id'];
						break;
					}
				}
			}
		
			if(isset($parseLocation['ward'])) {
				foreach ($wards as $ward) {
					if(strcasecmp($parseLocation['ward'], $ward['name']) === 0 && $ward['district_id'] == $project['district_id']) {
						$update['ward_id'] = $ward['id'];
						break;
					} else if($this->slug($parseLocation['ward']) == $this->slug($ward['name']) && $ward['district_id'] == $project['district_id']) {
						$update['ward_id'] = $ward['id'];
						break;
					}
				}
			}
		
			if($update) {
				\Yii::$app->db->createCommand()->update('ad_building_project', $update, 'id = ' . $project['id'])->execute();
			}
		}
	}
	
	public function parseLocation($project) {
		$location = trim($project['location']);
		 
		//backup '/^(((ngõ )|(số )|(((lô )|(Lô đất ))[a-z]*)|[a-z])?[0-9]\S*( (–|-) [a-z]?[0-9]\S*)?)/i'
		 
		preg_match('/^(((ngõ )|(số )|(((lô )|(Lô đất ))[a-z]*)|[a-z])?[0-9]\S*( (–|-) [a-z]?[0-9]\S*)?( ngõ [a-z]*[0-9]\S*)?)/i', $location, $matches);
		 
		$return = [];
		 
		if($matches) {
			$return['homeNo'] = preg_replace('/số /i', '', rtrim($matches[0], ','));
			$remain = trim(str_replace($matches[0], '', $location));
		} else {
			$remain = $location;
		}
		 
		$remainSplit = array_map('trim', explode(',', $remain));
		$streetKey = $this->streetDetect($remainSplit);
		 
		if($streetKey !== null) {
			$return['street'] = trim(preg_replace('/^((đường)|(Đường)|(phố))\s?/i', '', $remainSplit[$streetKey]));
			$remainSplit = array_slice($remainSplit, $streetKey + 1);
		}
		 
		$wardKey = $this->wardDetect($remainSplit);
		 
		if($wardKey !== null) {
			$return['ward'] = trim(preg_replace('/^((phường)|(thị trấn)|(xã)|(x\.)|(p\.))\s?/i', '', $remainSplit[$wardKey]));
	
			if($streetKey === null && $wardKey > 0) {
				$return['street'] = $remainSplit[$wardKey - 1];
			}
		}
		 
		$return['remainSplit'] = $remainSplit;
		 
		return $return;
	}
	
	public function streetDetect($remainSplit) {
		foreach ($remainSplit as $k => $rs) {
			if(preg_match('/^((đ|Đường)|(phố))/i', $rs)) {
				return $k;
			}
		}
		return null;
	}
	
	public function wardDetect($remainSplit) {
		foreach ($remainSplit as $k => $rs) {
			if(preg_match('/^((phường)|(thị trấn)|(xã)|(x\.)|(p\.))/i', $rs)) {
				return $k;
			}
		}
		return null;
	}
	
	function slug($str) {
		$str = trim(mb_strtolower($str, 'UTF-8'));
		$str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
		$str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
		$str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
		$str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
		$str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
		$str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
		$str = preg_replace('/(đ)/', 'd', $str);
		$str = preg_replace('/[^a-z0-9-\s]/', '', $str);
		$str = preg_replace('/([\s]+)/', '-', $str);
		return $str;
	}
}