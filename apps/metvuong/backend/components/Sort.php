<?php
namespace backend\components;

class Sort {
	private $models;
	private $sortByAttribute;
	
	public function __construct($models, $sortByAttribute = 'order') {
		$this->models = $models;
		$this->sortByAttribute = $sortByAttribute;
	}
	
	public function render() {
		
	}
	
	public function save() {
		foreach ($this->models as $model) {
			
		}
	}
}