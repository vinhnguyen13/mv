<?php

use yii\db\Schema;
use yii\db\Migration;

class m160127_025555_alter_ads_table extends Migration
{
    public function up()
    {
		$this->execute("ALTER TABLE `ad_city` ADD COLUMN `slug` VARCHAR(32) NULL AFTER `name`, CHANGE `geometry` `geometry` MEDIUMTEXT NULL;");
		$this->execute("ALTER TABLE `ad_district` ADD COLUMN `slug` VARCHAR(32) NULL AFTER `name`, CHANGE `geometry` `geometry` MEDIUMTEXT NULL;");
		$this->execute("ALTER TABLE `ad_ward` ADD COLUMN `slug` VARCHAR(32) NULL AFTER `name`;");
    	
    	$this->updateTable('ad_city');
    	$this->updateTable('ad_district');
    	$this->updateTable('ad_ward');
    }
    
    public function updateTable($table) {
    	$items = \Yii::$app->db->createCommand("SELECT * FROM `$table`")->queryAll();
    	$slugs = [];
    	foreach ($items as $item) {
    		$pre = empty($item['pre']) ? '' : $item['pre'] . ' ';
    		$slug = $this->slug($pre . $item['name']);
    		$slug = $this->uniqueSlug($slugs, $slug);
    		$slugs[] = $slug;
    		$this->execute("UPDATE `$table` SET `slug` = '$slug' WHERE `id` = {$item['id']};");
    	}
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
    
    function uniqueSlug($slugs, $slug, $increase = 0) {
    	$checkSlug = $increase ? $slug . '-' . $increase : $slug;
    	 
    	if(in_array($checkSlug, $slugs)) {
    		return $this->uniqueSlug($slugs, $slug, ++$increase);
    	} else {
    		return $checkSlug;
    	}
    }

    public function down()
    {
        $this->execute("ALTER TABLE `ad_city` DROP COLUMN `slug`, CHANGE `geometry` `geometry` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL;");
        $this->execute("ALTER TABLE `ad_district` DROP COLUMN `slug`, CHANGE `geometry` `geometry` TEXT CHARSET utf8 COLLATE utf8_general_ci NULL;");
        $this->dropColumn('ad_ward', 'slug');
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
