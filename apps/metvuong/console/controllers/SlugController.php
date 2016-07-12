<?php
namespace console\controllers;

use yii\console\Controller;
use common\components\Slug;
use yii\db\Query;

class SlugController extends Controller {
	
	private $slugsSaved = [];
	private $slug;
	private $connection;
	
	public function init() {
		$this->slug = new Slug();
		$this->connection = \Yii::$app->db;
	}
	
	public function actionUpdate() {
		$this->connection->createCommand("TRUNCATE TABLE `slug_search`")->execute();
		
		$cityQuery = (new Query())->select('id, name')->from('ad_city');
		$this->buildSlugs($cityQuery);

		$districtQuery = (new Query())->select([
			"`ad_district`.`id`",
			"IF(`ad_district`.`name` REGEXP '^-?[0-9]+$', CONCAT(`ad_city`.`name`, ' ', `ad_district`.`pre`, ' ', `ad_district`.`name`), CONCAT(`ad_city`.`name`, ' ', `ad_district`.`name`)) AS `name`"
		])->from('ad_district')->leftJoin('ad_city', '`ad_district`.`city_id` = `ad_city`.`id`');
		$this->buildSlugs($districtQuery);
		
		$wardQuery = $this->buildQueryBelongDistrict('ad_ward');
		$this->buildSlugs($wardQuery);
		
		$streetQuery = $this->buildQueryBelongDistrict('ad_street');
		$this->buildSlugs($streetQuery);
		
		$projectQuery = (new Query())->select('id, name')->from('ad_building_project')->orderBy('city_id');
		$this->buildSlugs($projectQuery, 'uniqueProjectSlug');
	}
	
	private function buildSlugs($query, $slugFunction = 'uniqueSlug') {
		$table = $query->from[0];
		$items = $query->all();
		
		$data = [];
		
		foreach ($items as $item) {
			$slug = $this->slug->slugify($item['name']);
			$slug = call_user_func([$this, $slugFunction], $slug, 0, $item);
			
			$this->connection->createCommand()->update($table, ['slug' => $slug], 'id = ' . $item['id'])->execute();
			
			$data[] = [$slug, $table, $item['id']];
		}
		
		$this->saveSlugs($data);
	}
	
	private function buildQueryBelongDistrict($table) {
		$query = (new Query())->select([
				"`$table`.`id`",
				"@district_name:=(IF(`ad_district`.`name` REGEXP '^-?[0-9]+$', CONCAT(`ad_city`.`name`, ' ', `ad_district`.`pre`, ' ', `ad_district`.`name`), CONCAT(`ad_city`.`name`, ' ', `ad_district`.`name`)))",
				"IF(`$table`.`name` REGEXP '^-?[0-9]+$', CONCAT(@district_name, ' ', `$table`.`pre`, ' ', `$table`.`name`), CONCAT(@district_name, ' ', `$table`.`name`)) AS `name`"
		])->from($table)->leftJoin('ad_city', "`$table`.`city_id` = `ad_city`.`id`")->leftJoin('ad_district', "`$table`.`district_id` = `ad_district`.`id`");
		
		return $query;
	}
	
	private function uniqueProjectSlug($slug, $increase = 0, $item) {
		if(in_array($slug, $this->slugsSaved)) {
			echo $slug . " is exist, try append city" . "\n";
			$project = (new Query())->select([
					"`ad_city`.`name` `city_name`",
					"IF(`ad_district`.`name` REGEXP '^-?[0-9]+$', CONCAT(`ad_district`.`pre`, ' ', `ad_district`.`name`), `ad_district`.`name`) AS `district_name`"
			])->from('ad_building_project')->leftJoin('ad_city', '`ad_building_project`.`city_id` = `ad_city`.`id`')->leftJoin('ad_district', '`ad_building_project`.`district_id` = `ad_district`.`id`')->where(['`ad_building_project`.`id`' => $item['id']])->one();
			
			$slugWithCity = $slug . '-' . $this->slug->slugify($project['city_name']);
			
			if(in_array($slugWithCity, $this->slugsSaved)) {
				echo $slugWithCity . " is exist, try append district" . "\n";
				$slugWithDistrict = $slugWithCity . '-' . $this->slug->slugify($project['district_name']);
				
				if(in_array($slugWithCity, $this->slugsSaved)) {
					echo $slugWithDistrict . " is exist, swith to normal" . "\n";
					return $this->uniqueSlug($slug, $increase, $item);
				} else {
					$this->slugsSaved[] = $slugWithDistrict;
						
					return $slugWithDistrict;
				}
			} else {
				$this->slugsSaved[] = $slugWithCity;
					
				return $slugWithCity;
			}
		} else {
			$this->slugsSaved[] = $slug;
			
			return $slug;
		}
	}
	
	private function uniqueSlug($slug, $increase = 0, $item) {
		$checkSlug = $increase ? $slug . '-' . $increase : $slug;
	
		if(in_array($checkSlug, $this->slugsSaved)) {
			return $this->uniqueSlug($slug, ++$increase);
		} else {
			$this->slugsSaved[] = $checkSlug;
				
			return $checkSlug;
		}
	}
	
	private function saveSlugs($data) {
		$this->connection->createCommand()->batchInsert('slug_search', ['slug', 'table', 'value'], $data)->execute();
	}
}