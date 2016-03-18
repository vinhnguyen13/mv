<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\helpers\ArrayHelper;

class m160318_034340_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_city` ADD COLUMN `bounding_box` VARCHAR(255) NULL AFTER `color`;");
		$this->execute("ALTER TABLE `ad_district` ADD COLUMN `bounding_box` VARCHAR(255) NULL AFTER `color`;");
		$this->execute("ALTER TABLE `ad_ward` ADD COLUMN `bounding_box` VARCHAR(255) NULL AFTER `color`;");
		
		$cities = ArrayHelper::index(\Yii::$app->db->createCommand("SELECT * FROM `ad_city`")->queryAll(), 'slug');
		
		$mapCity = [
			'ho-chi-minh' => 'tp-ho-chi-minh'
		];

		$color = '#FF0000';

		foreach($cities as $slug => $city) {
			$slug = isset($mapCity[$slug]) ? $mapCity[$slug] : $slug;
			$cityFile = json_decode(file_get_contents(Yii::getAlias('@console') . '/migrations/cities' . DIRECTORY_SEPARATOR . $slug . '.js'), true);
			
			$center = json_encode([round($cityFile['center']['lat'], 6), round($cityFile['center']['lng'], 6)]);
			
			$boundingBox = json_encode([
				'tl' => [round($cityFile['bounding_box']['tl']['lat'], 6), round($cityFile['bounding_box']['tl']['lng'], 6)],
				'br' => [round($cityFile['bounding_box']['br']['lat'], 6), round($cityFile['bounding_box']['br']['lng'], 6)]
			]);
			
			$geometries = [];
			
			foreach ($cityFile['geo']['geometry']['coordinates'] as $coordinate) {
				$coordinate = $coordinate[0];
				
				foreach ($coordinate as &$coo) {
					$coo[0] = round($coo[0], 6);
					$coo[1] = round($coo[1], 6);
					$coo = array_reverse($coo);
				}
				
				$geometries[] = $coordinate;
			}
			
			$geometries = json_encode($geometries);
			
			\Yii::$app->db->createCommand()->update('ad_city', [
				'geometry' => $geometries,
				'center' => $center,
				'color' => $color,
				'bounding_box' => $boundingBox
			], "id = {$city['id']}")->execute();
		}
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_city` DROP COLUMN `bounding_box`;");
        $this->execute("ALTER TABLE `ad_district` DROP COLUMN `bounding_box`;");
        $this->execute("ALTER TABLE `ad_ward` DROP COLUMN `bounding_box`;");
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
