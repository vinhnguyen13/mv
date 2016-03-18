<?php
namespace console\controllers;

use yii\console\Controller;
use yii\helpers\ArrayHelper;
use Yii;

class GeoController extends Controller {
	public function actionGeo() {
// 		ini_set('xdebug.var_display_max_depth', -1);
// 		ini_set('xdebug.var_display_max_children', -1);
// 		ini_set('xdebug.var_display_max_data', -1);
		
//		$files = array_diff(scandir(Yii::getAlias('@store') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'geometry'), array('.', '..'));
		
		$cities = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_city`")->queryAll(), 'slug');
		
		$mapCity = [
			'ho-chi-minh' => 'tp-ho-chi-minh'
		];
		
		$mapDistrict = [
			'Hà Nội' => [
				'Ba Vì' => 'Ba Vi',
				'Bắc Từ Liêm' => 'Từ Liêm',
				'Nam Từ Liêm' => 'Từ Liêm',
				'Ứng Hòa' => 'Ứng Hoà',
			],
			'Bình Dương' => [
				'Dầu Tiếng' => 'Dau Tieng',
				'Dĩ An' => 'Di An'
			],
			'Đà Nẵng' => [
				'Hòa Vang' => 'Hoà Vang',
			],
			'Hải Phòng' => [
				'Bạch Long Vĩ' => 'Bach Long Vi',
			],
			'Long An' => [
				'Tân Thạnh' => 'Tân Thành',
				'Thạnh Hóa' => 'Thanh Hóa',
			],
			'Bà Rịa Vũng Tàu' => [
				'Bà Rịa' => 'Ba Ria'
			],
			'An Giang' => [
				'Long Xuyên' => 'TP. Long Xuyên'
			],
			'Bắc Kạn' => [
				'Bắc Kạn' => 'Bac Kan',
				'Na Rì' => 'Na Ri'
			],
			'Bến Tre' => [
				'Mỏ Cày Bắc' => 'Mỏ Cày',
				'Mỏ Cày Nam' => 'Mỏ Cày'
			],
			'Bình Định' => [
				'Quy Nhơn' => 'Qui Nhơn',
				'Vân Canh' => 'Van Canh',
			],
			'Bình Phước' => [
				'Bù Đốp' => 'Bu Dop',
				'Đồng Phú' => 'Đồng Phù',
				'Đồng Xoài' => 'Dong Xoai',
			],
			'Bình Thuận' => [
				'Đảo Phú Quý' => 'Phú Quý',
				'Tánh Linh' => 'Tanh Linh',
			],
			'Cao Bằng' => [
				'Hòa An' => 'Hoà An',
				'Quảng Uyên' => 'Quảng Yên',
			],
			'Đắk Lắk' => [
				'Buôn Ma Thuột' => 'Buon Ma Thuot',
				"Ea H'Leo" => "Ea H'leo",
				'Krông Pắc' => 'Krông Pắk'
			],
			'Đắk Nông' => [
				'Dăk GLong' => 'Đăk Glong',
				'Dăk Mil' => 'Đăk Mil',
				"Dăk R'Lấp" => "Đăk R'Lấp",
				"Dăk Song"	=> "Dak Song",
			],
			'Điện Biên' => [
				'Điện Biên Phủ' => 'Điên Biên Phủ'
			],
			'Đồng Nai' => [
				'Biên Hòa' => 'Bien Hoa'
			],
			'Đồng Tháp' => [
				'Huyện Cao Lãnh' => 'Cao Lanh',
				'Huyện Hồng Ngự' => 'Hồng Ngự',
				'Tp. Cao Lãnh' => 'Cao Lãnh',
			],
			'Gia Lai' => [
				'AYun Pa' => 'Ayun Pa',
				'ChưPRông' => 'Chư Prông',
				'Đăk Đoa' => 'Đắk Đoa',
				'Đăk Pơ' => 'Đắk Pơ',
				'KBang' => "K'Bang",
				'Plei Ku' => 'Pleiku',
			],
			'Hậu Giang' => [
				'Vị Thủy' => 'Vị Thuỷ'
			],
			'Hòa Bình' => [
				'Lạc Thủy' => 'Lạc Thuỷ',
				'Yên Thủy' => 'Yên Thuỷ'
			],
			'Khánh Hòa' => [
				'Vạn Ninh' => 'Van Ninh'
			],
			'Kiên Giang' => [
				'U minh Thượng' => 'U Minh Thượng'
			],
			'Kon Tum' => [
				'KonTum' => 'Kon Tum'
			],
			'Lai Châu' => [
				'Than Uyên' => 'Thanh Uyen'
			],
			'Lạng Sơn' => [
				'Văn Lãng' => 'Vãn Lãng'
			],
			'Lào Cai' => [
				'Xi Ma Cai' => 'Si Ma Cai'
			],
			'Ninh Thuận' => [
				'Phan Rang - Tháp Chàm' => 'Phan Rang-Tháp Chàm'
			],
			'Phú Thọ' => [
				'Hạ Hòa' => 'Hạ Hoà',
				'Phù Ninh' => 'Phú Ninh',
				'Thanh Thủy' => 'Thanh Thuỷ'
			],
			'Phú Yên' => [
				'Tuy Hòa' => 'Tuy Hoa'
			],
			'Quảng Bình' => [
				'Tuyên Hóa' => 'Tuyen Hoa'
			],
			'Quảng Nam' => [
				'Tây Giang' => 'Tay Giang'
			],
			'Quảng Trị' => [
				'Đăk Rông' => 'Đa Krông',
				'Đảo Cồn cỏ' => 'Cồn Cỏ',
			],
			'Sóc Trăng' => [
				'Thạnh Trị' => 'Thanh Trì',
				
			],
			'Thái Bình' => [
				'Thái Thuỵ' => 'Thái Thụy'
			],
			'Thái Nguyên' => [
				'Định Hóa' => 'Định Hoá'
			],
			'Thanh Hóa' => [
				'Bỉm Sơn' => 'Bim Son',
				'Ngọc Lặc' => 'Ngọc Lạc',
				'Thanh Hóa' => 'Thanh Hóa City'
			],
			'Tiền Giang' => [
				'Gò Công' => 'Go Cong',
				'Huyện Cai Lậy' => 'Cai Lậy'
			],
			'Tuyên Quang' => [
				'Chiêm Hóa' => 'Chiêm Hoá',
				'Na Hang' => 'Nà Hang',
			],
			'Vĩnh Phúc' => [
				'Tam Dương' => 'Tam Đường'
			],
			'Yên Bái' => [
				'Mù Cang Chải' => 'Mù Căng Trai'
			]
		];
		
		$mapWards = [
			'Hồ Chí Minh' => [
				'Bình Chánh' => [
					'Tân Nhựt' => 'Tân Nhùt'
				],
				'Bình Tân' => [
					'Bình Hưng Hòa' => 'Bình Hưng  Hòa',
					'Bình Hưng Hòa A' => 'Bình Hưng  Hòa A'
				],
				'Cần Giờ' => [
					'An Thới Đông' => 'An Thíi Đông',
					'Tam Thôn Hiệp' => 'Tam Thôn HiÖp'
				],
				'Củ Chi' => [
					'An Phú' => 'Ân Phú',
					'Hòa Phú' => 'Hoà Phú',
					'Phú Hòa Đông' => 'Phú Hoà Đông',
				],
				'Quận 12' => [
					'Hiệp Thành' => 'Hiệp Thµnh',
					'Thạnh Xuân' => 'Th¹nh Xuân',
					'Thới An' => 'Thíi An'
				],
				'Quận 2' => [
					'An Phú' => 'Ân Phú'
				]
			]
		];
		
		$mapSlug = [
			'an-thii-dong' => 'an-thoi-dong',
			'tam-thon-hiop' => 'tam-thon-hiep',
			'hiep-th┬╡nh' => 'hiep-thanh',
			'thnh-xuan' => 'thanh-xuan',
			'thii-an' => 'thoi-an'
		];
		
		$color = '#FF0000';
		
		foreach($cities as $slug => $city) {
			$slug = isset($mapCity[$slug]) ? $mapCity[$slug] : $slug;
			
			$cityFile = json_decode(file_get_contents(Yii::getAlias('@store') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'geometry' . DIRECTORY_SEPARATOR . $slug . '.js'), true);

			$districts = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_district` WHERE city_id = {$city['id']}")->queryAll(), 'name');
			$districtsFile = ArrayHelper::index($cityFile['geo']['features'], function($e) { return $e['properties']['name']; });
			
			foreach ($districts as $districtName => $district) {
				$districtNameFile = isset($mapDistrict[$city['name']][$districtName]) ? $mapDistrict[$city['name']][$districtName] : $districtName;
				$districtFile = $districtsFile[$districtNameFile];

				$districtCenter = json_encode([round($districtFile['properties']['center_lat'], 6), round($districtFile['properties']['center_lng'], 6)]);
				$districtBoundingBox = json_encode([
					'tl' => [round($districtFile['wards']['bounding_box']['tl']['lat'], 6), round($districtFile['wards']['bounding_box']['tl']['lng'], 6)],
					'br' => [round($districtFile['wards']['bounding_box']['br']['lat'], 6), round($districtFile['wards']['bounding_box']['br']['lng'], 6)]
				]);
				
				$districtGeometry = [];
				
				foreach ($districtFile['geometry']['coordinates'] as $coordinate) {
					$coordinate = $coordinate[0];
					
					foreach ($coordinate as &$coo) {
						$coo[0] = round($coo[0], 6);
						$coo[1] = round($coo[1], 6);
						$coo = array_reverse($coo);
					}
					
					$districtGeometry[] = $coordinate;
				}
				
				$districtGeometry = json_encode($districtGeometry);
				
				\Yii::$app->db->createCommand()->update('ad_district', [
					'geometry' => $districtGeometry,
					'center' => $districtCenter,
					'color' => $color,
					'bounding_box' => $districtBoundingBox
				], "id = {$district['id']}")->execute();
				
				// Ward
				$wards = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_ward` WHERE district_id = {$district['id']}")->queryAll(), function($ward){ return trim(is_numeric($ward['name']) ? $ward['pre'] . ' ' . $ward['name'] : $ward['name']); });
				$wardsFile = ArrayHelper::index($districtFile['wards']['geo']['features'], function($e) { return $e['properties']['name']; });
				
				foreach ($wards as $wardName => $ward) {
					$wardNameFile = isset($mapWards[$city['name']][$districtName][$wardName]) ? $mapWards[$city['name']][$districtName][$wardName] : $wardName;
					
					if(isset($wardsFile[$wardNameFile])) {
						$wardFile = $wardsFile[$wardNameFile];
						
						$wardCenter = json_encode([round($wardFile['properties']['center_lat'], 6), round($wardFile['properties']['center_lng'], 6)]);
						$wardGeometry = [];
						
						foreach ($wardFile['geometry']['coordinates'] as $coordinate) {
							$coordinate = $coordinate[0];
								
							foreach ($coordinate as &$coo) {
								$coo[0] = round($coo[0], 6);
								$coo[1] = round($coo[1], 6);
								$coo = array_reverse($coo);
							}
								
							$wardGeometry[] = $coordinate;
						}
						
						$wardGeometry = json_encode($wardGeometry);
						
						$wardSlug = isset($mapSlug[$wardFile['properties']['slug']]) ? $mapSlug[$wardFile['properties']['slug']] : $wardFile['properties']['slug'];
						$url = 'https://www.zita.vn/api/provinces/' . $cityFile['slug'] . '/districts/' . $districtFile['properties']['slug'] . '/wards/' . $wardSlug . '.json';
						
						$wardBoundingBox = null;
						
						if($getWard = @file_get_contents($url)) {
							$getWard = json_decode($getWard, true);
							
							$wardBoundingBox = json_encode([
								'tl' => [round($getWard['bounding_box']['tl']['lat'], 6), round($getWard['bounding_box']['tl']['lng'], 6)],
								'br' => [round($getWard['bounding_box']['br']['lat'], 6), round($getWard['bounding_box']['br']['lng'], 6)]
							]);
						} else {
							echo $url;
						}
						
						\Yii::$app->db->createCommand()->update('ad_ward', [
							'geometry' => $wardGeometry,
							'center' => $wardCenter,
							'color' => $color,
							'bounding_box' => $wardBoundingBox
						], "id = {$ward['id']}")->execute();
					}
				}
			}
// 			$slug = isset($mapCity[$slug]) ? $mapCity[$slug] : $slug;
// 			$cityFile = json_decode(file_get_contents(Yii::getAlias('@store') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'geometry' . DIRECTORY_SEPARATOR . $slug . '.js'), true);
					
// 			$districts = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_district` WHERE city_id = {$city['id']}")->queryAll(), 'name');
// 			$districtFiles = ArrayHelper::index($cityFile['geo']['features'], function($e) { return $e['properties']['name']; });
			
// 			echo $city['name'];
// 			echo '<div style="margin-left: 40px;">';
// 			foreach($districts as $districtName => $district) {
// 				$districtName = isset($mapDistrict[trim($city['name'])][$districtName]) ? $mapDistrict[trim($city['name'])][$districtName] : $districtName;
				
// 				echo $districtName;
				
// 				if(isset($districtFiles[$districtName])) {
// 					$wards = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_ward` WHERE district_id = {$district['id']}")->queryAll(), 'name');
// 					$wardFiles = ArrayHelper::index($districtFiles[$districtName]['wards']['geo']['features'], function($e) { return $e['properties']['name']; });
					
// 					echo '<div style="margin-left: 80px;">';
// 					echo count($wards) . ' ' . count($wardFiles) . '<br />';
// 					foreach ($wards as $wardName => $ward) {
// 						$wardName = $ward['pre'] ? $ward['pre'] . ' ' . $wardName : $wardName;
// 						$wardName = isset($mapWards[trim($city['name'])][$districtName][$wardName]) ? $mapWards[trim($city['name'])][$districtName][$wardName] : $wardName;
// 						if(!isset($wardFiles[$wardName])) {
// 							echo $wardName . '<br />';
// 						}
// 					}
// 					echo '</div>';
// 				}
// 			}
			
			
// 			foreach($districts as $name => $district) {
// 				$name = isset($mapDistrict[trim($city['name'])][$name]) ? $mapDistrict[trim($city['name'])][$name] : $name;
// 				if(isset($districtFiles[$name])) {
// 					$wards = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_ward` WHERE district_id = {$district['id']}")->queryAll(), 'name');
// 					var_dump($wards);
// // 					$wards = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_ward` WHERE district_id = {$district['id']}")->queryAll(), 'name');
					
// // 					var_dump($districtFiles[$name]['wards']);
// // 					echo $name;
// // 					echo '<div style="margin-left: 80px;">';
// // 					echo '</div>';
// 					break;
// 				}
// 				break;
// 			}
			
// 			echo '</div>';
			
			break;
		}
	}
}