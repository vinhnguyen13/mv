<?php
namespace console\controllers;

use yii\console\Controller;
use frontend\models\Elastic;
use yii\db\ActiveRecord;
use vsoft\ad\models\AdProduct;

class ElasticController extends Controller {
	public function actionBuildIndex() {
		$ELASTIC = NEW ELASTIC();
		$ELASTIC->CONNECT();
		
		$CITIES = $THIS->GETTERMFROMDB('AD_CITY');
		
		FOREACH($CITIES AS $CITY) {
			$CITYTERM = $THIS->BUILDTERM('CITY', $CITY['ID'], $CITY['ID'], $CITY['NAME'], $CITY['NAME'], INTVAL($THIS->COUNTPRODUCTS(['CITY_ID' => $CITY['ID']])));
			$ELASTIC->INDEX($CITYTERM);
			
			$DISTRICTS = $THIS->GETTERMFROMDB('AD_DISTRICT', ['CITY_ID' => $CITY['ID']]);
			
			FOREACH ($DISTRICTS AS $DISTRICT) {
				$DISTRICTNAME = $DISTRICT['PRE'] ? $DISTRICT['PRE'] . ' ' . $DISTRICT['NAME'] : $DISTRICT['NAME'];
				$DISTRICTFULLNAME = $DISTRICTNAME . ', ' . $CITY['NAME'];
				$DISTRICTTERM = $THIS->BUILDTERM('DISTRICT', $DISTRICT['ID'], $CITY['ID'], $DISTRICTNAME, $DISTRICTFULLNAME, INTVAL($THIS->COUNTPRODUCTS(['DISTRICT_ID' => $DISTRICT['ID']])));
				$ELASTIC->INDEX($DISTRICTTERM);
				
				$THIS->BUILDTERMSBELONGDISTRICT('AD_WARD', 'WARD', $CITY['ID'], $DISTRICT['ID'], $DISTRICTFULLNAME);
				$THIS->BUILDTERMSBELONGDISTRICT('AD_STREET', 'STREET', $CITY['ID'], $DISTRICT['ID'], $DISTRICTFULLNAME);
				$THIS->BUILDTERMSBELONGDISTRICT('AD_BUILDING_PROJECT', 'PROJECT_BUILDING', $CITY['ID'], $DISTRICT['ID'], $DISTRICTFULLNAME, FALSE);
			}
		}
	}
	public function actionRebuildIndex() {
		$this->actionRemoveIndex();
		$this->actionBuildIndex();
	}
	public function actionRemoveIndex() {
		
	}
	public function actionRebuildTotal() {
		
	}
	private function updateTotal($id, $type, $total) {
		
	}
	private function addTerm($type, $term) {
		
	}
	private function addTerms($type, $terms) {
		
	}
	private function buildTermsBelongDistrict($table, $type, $cityId, $districtId, $districtFullName, $hasPre = true) {
		$elastic = new Elastic();
		$elastic->connect();
		
		$terms = $this->getTermFromDb($table, ['district_id' => $districtId]);
		
		foreach ($terms as $term) {
			$name = $term['name'];
			
			if($hasPre) {
				$name = $term['pre'] ? $term['pre'] . ' ' . $name : $name;
			}
			
			$fullName = $name . ', ' . $districtFullName;
			
			$buildTerm = $this->buildTerm($type, $term['id'], $cityId, $name, $fullName, intval($this->countProducts([$type . "_id" => $term['id']])));
			
			$buildTerm['body']['district_id'] = $districtId;
			
			$elastic->index($buildTerm);
		}
	}
	private function getTermFromDb($table, $where = []) {
		return ActiveRecord::find()->from($table)->where($where)->asArray(true)->all();
	}
	private function countProducts($where) {
		$p = AdProduct::find()->select(['count' => 'COUNT(*)'])->where($where)->asArray(true)->one();
		return $p['count'];
	}
	private function buildTerm($type, $id, $cityId, $name, $fullName, $total = 0) {
		return $params = [
			'index' => 'term',
			'type' => $type,
			'id' => $id,
			'body' => [
				'city_id'		=> $cityId,
				'full_name' 	=> $fullName,
				'name'			=> $name,
				'search_field'	=> Elastic::transform($name),
				'total'			=> $total
			]
		];
	}
}