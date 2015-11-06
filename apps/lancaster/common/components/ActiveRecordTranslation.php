<?php
namespace common\components;

use yii\db\ActiveRecord;
use lajax\translatemanager\models\Language;
use yii\base\UnknownPropertyException;

class ActiveRecordTranslation extends ActiveRecord {
	
	public static $isInitTranslation = false;
	public static $translationTable;
	public static $relationalKey;
	public static $languageKey = 'language_id';
	public static $translationFieldName = 'translation';
	public static $forceShowLang;
	
	private static $translationColumns = [];
	private static $translationFields = [];
	private static $translationPrimaryKey;
	
	public function translateField($form, $languageCode, $attribute, $options = []) {
		$name = $this->formName() . '[' . static::$translationFieldName . '][' . $languageCode . '][' . $attribute . ']';
		$id = str_replace(['[]', '][', '[', ']', ' ', '.'], ['', '-', '-', '', '-', '-'], $name);
		
		$defaulOptions = [
			'inputOptions' => [
				'class' => 'form-control',
				'name'	=> $name,
				'id'	=> $id,
				'value'	=> $this->translate($languageCode, $attribute)
			]
		];
		
		return $form->field($this, $attribute, array_merge($defaulOptions, $options));
	}
	
	public function load($data, $formName = null)
	{
		$this->loadTranslate();
		
		$translationFieldName = static::$translationFieldName;
		$formName = $formName === null ? $this->formName() : $formName;
		
		if(isset($data[$formName][$translationFieldName])) {
			$this->$translationFieldName = $data[$formName][$translationFieldName];
		}

		return parent::load($data);
	}
	
	public function translate($languageCode, $attribute, $value = FALSE) {
		if(in_array($attribute, self::$translationFields)) {
			$this->loadTranslate();
			
			$translationFieldName = static::$translationFieldName;
			$translationField = &$this->$translationFieldName;
				
			if($value !== FALSE) {
				$translationField[$languageCode][$attribute] = $value;
			} else {
				if($translationField[$languageCode]) {
					return $translationField[$languageCode][$attribute];
				} else {
					return '';
				}
			}
		} else {
			throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $attribute);
		}
	}
	
	private function loadTranslate() {
		$translationFieldName = static::$translationFieldName;
		
		if(!isset($this->$translationFieldName)) {
			self::$translationFields[$translationFieldName] = $translationFieldName;
			$this->$translationFieldName = [];

			$oldTranslationFieldName = 'old_' . $translationFieldName;
			self::$translationFields[$oldTranslationFieldName] = $oldTranslationFieldName;
			$this->$oldTranslationFieldName = [];
			
			unset(self::$translationFields[$translationFieldName]);
			unset(self::$translationFields[$oldTranslationFieldName]);
			
			$languages = Language::getLanguageNames(true);
			$translationField = &$this->$translationFieldName;
			
			foreach ($languages as $languageCode => $languageName) {
				$translationField[$languageCode] = null;
			}
			
			if(!$this->isNewRecord) {
				$sql = "SELECT * FROM `" . static::$translationTable . "` WHERE `" . static::$relationalKey . "` = " . $this->primaryKey;
				$records = $this->getDb()->createCommand($sql)->queryAll();
				
				foreach ($records as $record) {
					$languageCode = $record[static::$languageKey];

					unset($record[static::$relationalKey]);
					unset($record[static::$languageKey]);
					
					$translationField[$languageCode] = $record;
				}
			}
			
			$this->$oldTranslationFieldName = $this->$translationFieldName;
		}
	}
	
	public function __construct($config = []) {
		self::initTranslation();
		
		foreach (self::$translationColumns as $column) {
			self::$translationFields[] = $column;
			$this->$column = null;
		}
		
		parent::__construct($config);
	}
	
	public function __set($name, $value) {
		if(in_array($name, self::$translationFields)) {
			$this->$name = $value;
		} else {
			parent::__set($name, $value);
		}
	}
	
	public function beforeDelete() {
		$sql = "DELETE FROM `" . static::$translationTable . "` WHERE `" . static::$relationalKey . "` = " . $this->primaryKey;
		$this->getDb()->createCommand($sql)->execute();
		
		return parent::beforeDelete();
	}
	
	public function afterSave($insert, $changedAttributes) {
		$translationFieldName = static::$translationFieldName;
		$translationField = $this->$translationFieldName;
		
		if($insert) {
			foreach($translationField as $languageCode => $translation) {
				if(array_filter($translation)) {
					$this->insertTranslation($languageCode, $translation);
				}
			}
		} else {
			$oldTranslationFieldName = 'old_' . $translationFieldName;
			$oldTranslationField = $this->$oldTranslationFieldName;
			
			$totalRow = count(array_filter($oldTranslationField));
			$totalDelete = 0;
			
			foreach($translationField as $languageCode => $translation) {
				if(array_filter($translation)) {
					if($oldTranslationField[$languageCode]) {
						$this->updateTranslation($languageCode, $translation);
					} else {
						$this->insertTranslation($languageCode, $translation);
						$totalRow++;
					}
				} else {
					if($oldTranslationField[$languageCode]) {
						$condition = "`" . static::$relationalKey . "` = :" . static::$relationalKey . " AND `" . static::$languageKey . "` = :" . static::$languageKey;
						$params = [
							':' . static::$relationalKey => $this->primaryKey,
							':' . static::$languageKey => $languageCode
						];
						
						$this->getDb()->createCommand()->delete(static::$translationTable, $condition, $params)->execute();
						$totalDelete++;
					}
				}
			}
			
			if($totalRow == $totalDelete) {
				$this->delete();
			}
		}
	}
	
	public function insertTranslation($languageCode, $translation) {
		$command = $this->getDb()->createCommand();
		
		$translation[static::$relationalKey] = $this->primaryKey;
		$translation[static::$languageKey] = $languageCode;
		$command->insert(static::$translationTable, $translation)->execute();
	}
	
	public function updateTranslation($languageCode, $translation) {
		$command = $this->getDb()->createCommand();
	
		$condition = "`" . static::$relationalKey . "` = :" . static::$relationalKey . " AND `" . static::$languageKey . "` = :" . static::$languageKey;
		$params = [
			':' . static::$relationalKey => $this->primaryKey,
			':' . static::$languageKey => $languageCode
		];
		$command->update(static::$translationTable, $translation, $condition, $params)->execute();
	}
	
	public static function find() {
		self::initTranslation();
		
		$tableName = static::tableName();
		$selectColumns = ["$tableName.*"];
		
		foreach (self::$translationColumns as $column) {
			$selectColumns[] = "`" . static::$translationTable . "`.`$column` AS `$column`";
		}
		
		$lang = \Yii::$app->language;
		
		if(\Yii::$app->id == 'app-backend') {
			$join = 'LEFT JOIN';
			if(static::$forceShowLang) {
				$lang = static::$forceShowLang;
			}
		} else {
			$join = 'INNER JOIN';
		}
		
		return parent::find()->select($selectColumns)
							->join($join, static::$translationTable, "`$tableName`.`" . self::$translationPrimaryKey . "` = `" . static::$translationTable . "`.`" . static::$relationalKey . "` AND `" . static::$languageKey . "` = :language_id", [':language_id' => $lang]);
	}
	
	public static function initTranslation() {
		if(!self::$isInitTranslation) {
			$pks = self::primaryKey();
			self::$translationPrimaryKey = $pks[0];
			
			if(!static::$translationTable) {
				static::$translationTable = str_replace(['{{%', '}}'], '', static::tableName()) . '_translation';
			}
			
			if(!static::$relationalKey) {
				static::$relationalKey = self::$translationPrimaryKey;
			}

			$columns = self::getDb()->getTableSchema(static::$translationTable)->columns;
			
			unset($columns[static::$relationalKey]);
			unset($columns[static::$languageKey]);
			
			foreach ($columns as $column => $v) {
				self::$translationColumns[] = $column;
			}
			
			self::$isInitTranslation = true;
		}
	}
}