<?php

use yii\db\Schema;
use yii\db\Migration;

class m151118_042612_create_ads_table extends Migration
{
    public function up()
    {
    	$this->execute("CREATE TABLE `ad_investor` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `name` varchar(32) NOT NULL,
						  `address` varchar(255) DEFAULT NULL,
						  `phone` varchar(32) DEFAULT NULL,
						  `fax` varchar(32) DEFAULT NULL,
						  `website` varchar(255) DEFAULT NULL,
						  `email` varchar(255) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    	$this->execute("CREATE TABLE `ad_building_project` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `name` varchar(32) NOT NULL,
						  `location` varchar(255) DEFAULT NULL,
						  `investment_type` varchar(255) DEFAULT NULL,
						  `land_area` varchar(32) DEFAULT NULL,
						  `commercial_leasing_area` varchar(32) DEFAULT NULL,
						  `apartment_no` varchar(32) DEFAULT NULL,
						  `floor_no` varchar(32) DEFAULT NULL,
						  `facilities` varchar(255) DEFAULT NULL,
						  `hotline` varchar(32) DEFAULT NULL,
						  `website` varchar(255) DEFAULT NULL,
						  `lng` float DEFAULT NULL,
						  `lat` float DEFAULT NULL,
						  `gallery` text,
						  `video` text,
						  `progress` text,
						  `apartment_area` text,
						  `commercial_area` text,
						  `townhouse_area` text,
						  `office_area` text,
						  `created_at` int(11) NOT NULL,
						  `updated_at` int(11) DEFAULT NULL,
						  `status` tinyint(1) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_investor_building_project` (
						  `building_project_id` int(11) DEFAULT NULL,
						  `investor_id` int(11) DEFAULT NULL,
						  KEY `building_project_id&building_project:id` (`building_project_id`),
						  KEY `investor_id&investor:id` (`investor_id`),
						  CONSTRAINT `building_project_id&building_project:id` FOREIGN KEY (`building_project_id`) REFERENCES `ad_building_project` (`id`),
						  CONSTRAINT `investor_id&investor:id` FOREIGN KEY (`investor_id`) REFERENCES `ad_investor` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_category` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `name` varchar(32) NOT NULL,
						  `status` tinyint(1) DEFAULT '1' COMMENT '1: actived, 0: deactived',
						  `order` int(11) DEFAULT NULL,
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_city` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `name` varchar(32) NOT NULL,
						  `status` tinyint(1) NOT NULL DEFAULT '1',
						  PRIMARY KEY (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_district` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `city_id` int(11) NOT NULL,
						  `name` varchar(32) NOT NULL,
						  `status` tinyint(1) NOT NULL DEFAULT '1',
						  PRIMARY KEY (`id`),
						  KEY `district:city_id&city:id` (`city_id`),
						  CONSTRAINT `district:city_id&city:id` FOREIGN KEY (`city_id`) REFERENCES `ad_city` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_ward` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `district_id` int(11) NOT NULL,
						  `name` varchar(32) NOT NULL,
						  `status` tinyint(1) NOT NULL DEFAULT '1',
						  PRIMARY KEY (`id`),
						  KEY `ward:district_id&district:id` (`district_id`),
						  CONSTRAINT `ward:district_id&district:id` FOREIGN KEY (`district_id`) REFERENCES `ad_district` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_street` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `district_id` int(11) NOT NULL,
						  `ward_id` int(11) DEFAULT NULL,
						  `name` varchar(255) NOT NULL,
						  `status` tinyint(1) DEFAULT '1',
						  PRIMARY KEY (`id`),
						  KEY `street:district_id&district_id` (`district_id`),
						  KEY `street:ward_id&ward:id` (`ward_id`),
						  CONSTRAINT `street:district_id&district_id` FOREIGN KEY (`district_id`) REFERENCES `ad_district` (`id`),
						  CONSTRAINT `street:ward_id&ward:id` FOREIGN KEY (`ward_id`) REFERENCES `ad_ward` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_product` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `category_id` int(11) NOT NULL,
						  `project_building_id` int(11) DEFAULT NULL,
						  `user_id` int(11) DEFAULT NULL,
						  `city_id` int(11) NOT NULL,
						  `district_id` int(11) NOT NULL,
						  `ward_id` int(11) DEFAULT NULL,
						  `street_id` int(11) DEFAULT NULL,
						  `type` tinyint(1) NOT NULL COMMENT '1: nhà đất bán, 2: nhà đất cho thuê, 3: cần mua, 4: cần thuê',
						  `title` varchar(255) NOT NULL,
						  `content` varchar(3200) NOT NULL,
						  `area` int(11) DEFAULT NULL,
						  `price` int(11) DEFAULT NULL,
						  `price_type` tinyint(1) DEFAULT NULL,
						  `start_date` int(11) NOT NULL,
						  `end_date` int(11) NOT NULL,
						  `priority` tinyint(1) NOT NULL DEFAULT '10' COMMENT 'độ ưu tiên từ 10 -> 0. mặc định tin thường sẽ có giá trị là 10, giá trị ưu tiên càng thấp sẽ được hiển thị ở top',
						  `view` int(11) NOT NULL DEFAULT '0',
						  `created_at` int(11) NOT NULL,
						  `updated_at` int(11) DEFAULT NULL,
						  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1: actived, 0: deactived',
						  PRIMARY KEY (`id`),
						  KEY `ad_product:category_id&ad_category:id` (`category_id`),
						  KEY `ad_product:city_id&city:id` (`city_id`),
						  KEY `ad_product:district_id&district:id` (`district_id`),
						  KEY `ad_product:ward_id&ward:id` (`ward_id`),
						  KEY `ad_product:street_id&street_id` (`street_id`),
						  KEY `ad_product:project_building_id&project_building:id` (`project_building_id`),
						  CONSTRAINT `ad_product:category_id&ad_category:id` FOREIGN KEY (`category_id`) REFERENCES `ad_category` (`id`),
						  CONSTRAINT `ad_product:city_id&city:id` FOREIGN KEY (`city_id`) REFERENCES `ad_city` (`id`),
						  CONSTRAINT `ad_product:district_id&district:id` FOREIGN KEY (`district_id`) REFERENCES `ad_district` (`id`),
						  CONSTRAINT `ad_product:project_building_id&project_building:id` FOREIGN KEY (`project_building_id`) REFERENCES `ad_building_project` (`id`),
						  CONSTRAINT `ad_product:street_id&street_id` FOREIGN KEY (`street_id`) REFERENCES `ad_street` (`id`),
						  CONSTRAINT `ad_product:ward_id&ward:id` FOREIGN KEY (`ward_id`) REFERENCES `ad_ward` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_images` (
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `product_id` int(11) DEFAULT NULL,
						  `file_name` varchar(255) NOT NULL,
						  `uploaded_at` int(11) NOT NULL,
						  PRIMARY KEY (`id`),
						  KEY `ad_images:product_id&ad_product` (`product_id`),
						  CONSTRAINT `ad_images:product_id&ad_product` FOREIGN KEY (`product_id`) REFERENCES `ad_product` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    	$this->execute("CREATE TABLE `ad_product_addition_info` (
						  `product_id` int(11) NOT NULL,
						  `facade_width` int(11) DEFAULT NULL,
						  `land_width` int(11) DEFAULT NULL,
						  `home_direction` tinyint(4) DEFAULT NULL,
						  `facade_direction` tinyint(4) DEFAULT NULL,
						  `floor_no` int(11) DEFAULT NULL,
						  `room_no` int(11) DEFAULT NULL,
						  `toilet_no` int(11) DEFAULT NULL,
						  `interior` varchar(3200) DEFAULT NULL,
						  UNIQUE KEY `ad_product_addition_info:product_id&ad_product:id` (`product_id`),
						  CONSTRAINT `ad_product_addition_info:product_id&ad_product:id` FOREIGN KEY (`product_id`) REFERENCES `ad_product` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		$this->execute("CREATE TABLE `ad_contact_info` (
						  `product_id` int(11) NOT NULL,
						  `name` varchar(32) DEFAULT NULL,
						  `address` varchar(255) DEFAULT NULL,
						  `phone` varchar(32) DEFAULT NULL,
						  `mobile` varchar(32) NOT NULL,
						  `email` varchar(255) DEFAULT NULL,
						  UNIQUE KEY `ad_contact_info:product_id&ad_product:id` (`product_id`),
						  CONSTRAINT `ad_contact_info:product_id&ad_product:id` FOREIGN KEY (`product_id`) REFERENCES `ad_product` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		$this->execute("CREATE TABLE `ad_product_geocoding` (
						  `product_id` int(11) DEFAULT NULL,
						  `lng` float NOT NULL,
						  `lat` float NOT NULL,
						  UNIQUE KEY `ad_product_geocoding:product_id&product:id` (`product_id`),
						  CONSTRAINT `ad_product_geocoding:product_id&product:id` FOREIGN KEY (`product_id`) REFERENCES `ad_product` (`id`)
						) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		
		
    }

    public function down()
    {
        $this->dropTable("ad_images");
        $this->dropTable("ad_product_geocoding");
        $this->dropTable("ad_product_addition_info");
        $this->dropTable("ad_contact_info");
        $this->dropTable("ad_investor_building_project");
        $this->dropTable("ad_investor");
        $this->dropTable("ad_product");
        $this->dropTable("ad_building_project");
        $this->dropTable("ad_category");
        $this->dropTable("ad_street");
        $this->dropTable("ad_ward");
        $this->dropTable("ad_district");
        $this->dropTable("ad_city");
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
