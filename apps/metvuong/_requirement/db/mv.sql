/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50626
Source Host           : localhost:3306
Source Database       : mv

Target Server Type    : MYSQL
Target Server Version : 50626
File Encoding         : 65001

Date: 2015-10-28 10:38:18
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for blog_catalog
-- ----------------------------
DROP TABLE IF EXISTS `blog_catalog`;
CREATE TABLE `blog_catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `surname` varchar(128) NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `is_nav` int(11) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '50',
  `page_size` int(11) NOT NULL DEFAULT '10',
  `template` varchar(255) NOT NULL DEFAULT 'post',
  `redirect_url` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_nav` (`is_nav`),
  KEY `sort_order` (`sort_order`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_catalog
-- ----------------------------

-- ----------------------------
-- Table structure for blog_comment
-- ----------------------------
DROP TABLE IF EXISTS `blog_comment`;
CREATE TABLE `blog_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `url` varchar(128) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `FK_comment_post` FOREIGN KEY (`post_id`) REFERENCES `blog_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_comment
-- ----------------------------

-- ----------------------------
-- Table structure for blog_post
-- ----------------------------
DROP TABLE IF EXISTS `blog_post`;
CREATE TABLE `blog_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `brief` text,
  `content` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `surname` varchar(128) NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `click` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  KEY `FK_post_user` (`user_id`),
  CONSTRAINT `FK_post_catalog` FOREIGN KEY (`catalog_id`) REFERENCES `blog_catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_post_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_post
-- ----------------------------

-- ----------------------------
-- Table structure for blog_tag
-- ----------------------------
DROP TABLE IF EXISTS `blog_tag`;
CREATE TABLE `blog_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `frequency` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `frequency` (`frequency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of blog_tag
-- ----------------------------

-- ----------------------------
-- Table structure for cms_catalog
-- ----------------------------
DROP TABLE IF EXISTS `cms_catalog`;
CREATE TABLE `cms_catalog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `brief` varchar(1022) DEFAULT NULL,
  `content` text,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `is_nav` int(11) NOT NULL DEFAULT '1',
  `sort_order` int(11) NOT NULL DEFAULT '50',
  `page_type` varchar(255) NOT NULL DEFAULT 'page',
  `page_size` int(11) NOT NULL DEFAULT '10',
  `template_list` varchar(255) NOT NULL DEFAULT 'list',
  `template_show` varchar(255) NOT NULL DEFAULT 'show',
  `template_page` varchar(255) NOT NULL DEFAULT 'page',
  `redirect_url` varchar(255) DEFAULT NULL,
  `click` int(11) DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_nav` (`is_nav`),
  KEY `sort_order` (`sort_order`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cms_catalog
-- ----------------------------
INSERT INTO `cms_catalog` VALUES ('1', '0', 'Projects', 'Projects', 'Projects', '<p>Projects</p>\r\n', '', '', '', null, '1', '50', 'page', '10', 'list', 'show', 'page', '', '0', '1', '1443756042', '1443756042');
INSERT INTO `cms_catalog` VALUES ('2', '0', 'News', 'News', 'News', 'News', null, null, null, null, '1', '50', 'page', '10', 'list', 'show', 'page', null, '0', '1', '0', '0');
INSERT INTO `cms_catalog` VALUES ('3', '2', 'Bất động sản', null, null, null, null, null, null, null, '1', '50', 'page', '10', 'list', 'show', 'page', null, '0', '1', '0', '0');
INSERT INTO `cms_catalog` VALUES ('4', '3', 'Bất động sản lel 1', null, null, null, null, null, null, null, '1', '50', 'page', '10', 'list', 'show', 'page', null, '0', '1', '0', '0');
INSERT INTO `cms_catalog` VALUES ('5', '2', 'Dự án', null, null, null, null, null, null, null, '1', '50', 'page', '10', 'list', 'show', 'page', null, '0', '1', '0', '0');
INSERT INTO `cms_catalog` VALUES ('6', '1', 'Projects lel 1-1', null, null, null, null, null, null, null, '1', '50', 'page', '10', 'list', 'show', 'page', null, '0', '1', '0', '0');
INSERT INTO `cms_catalog` VALUES ('7', '1', 'Projects lel 1-2', null, null, null, null, null, null, null, '1', '50', 'page', '10', 'list', 'show', 'page', null, '0', '1', '0', '0');
INSERT INTO `cms_catalog` VALUES ('8', '6', 'Projects lel 1-1-1', null, null, null, null, null, null, null, '1', '50', 'page', '10', 'list', 'show', 'page', null, '0', '1', '0', '0');

-- ----------------------------
-- Table structure for cms_show
-- ----------------------------
DROP TABLE IF EXISTS `cms_show`;
CREATE TABLE `cms_show` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catalog_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `surname` varchar(128) DEFAULT NULL,
  `brief` varchar(1022) DEFAULT NULL,
  `content` text,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `template_show` varchar(255) NOT NULL DEFAULT 'show',
  `author` varchar(255) NOT NULL DEFAULT 'admin',
  `click` int(11) NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `created_by` int(11) DEFAULT '0',
  `updated_by` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `catalog_id` (`catalog_id`),
  KEY `status` (`status`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `FK_cms_catalog` FOREIGN KEY (`catalog_id`) REFERENCES `cms_catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cms_show
-- ----------------------------
INSERT INTO `cms_show` VALUES ('2', '1', 'Toyota Việt Nam ra mắt Hilux 2016, 3 phiên bản, giá từ 693 triệu đồng việt', 'toyota-viet-nam-ra-mat-hilux-2016-3-phien-ban-gia-tu-693-trieu-dong-viet', null, '', '<p style=\"text-align:center\"><span style=\"background-color:rgb(255, 255, 255); color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px\">Thương hiệu </span><a class=\"Tinhte_XenTag_TagLink\" href=\"https://tinhte.vn/tags/nikon/\" style=\"color: rgb(23, 96, 147); text-decoration: none; border-radius: 0px; padding: 0px 3px; margin: 0px -3px; font-family: Helvetica, Arial, sans-serif; font-size: 14.6667px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 20.5333px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\">Nikon</a><span style=\"background-color:rgb(255, 255, 255); color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px\"> được ra đời v&agrave;o ng&agrave;y 25 th&aacute;ng 07 năm 1917 v&agrave; trong gần hai năm tới đ&acirc;y, họ sẽ tổ chức ch&agrave;o mừng kỷ niệm 100 năm ng&agrave;y th&agrave;nh lập của m&igrave;nh. Song song với sự kiện n&agrave;y, Nikon đ&atilde; quyết định mở một </span><a class=\"Tinhte_XenTag_TagLink\" href=\"https://tinhte.vn/tags/bao-tang/\" style=\"color: rgb(23, 96, 147); text-decoration: none; border-radius: 0px; padding: 0px 3px; margin: 0px -3px; font-family: Helvetica, Arial, sans-serif; font-size: 14.6667px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 20.5333px; orphans: auto; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\">bảo t&agrave;ng</a><span style=\"background-color:rgb(255, 255, 255); color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px\"> mới ri&ecirc;ng của m&igrave;nh v&agrave; n&oacute; sẽ được ch&iacute;nh thức mở cửa v&agrave;o ng&agrave;y 17 th&aacute;ng 10 năm 2015 tới đ&acirc;y. Bảo t&agrave;ng Nikon n&agrave;y sẽ được đặt tại tầng hai của to&agrave; nh&agrave; trụ sở ch&iacute;nh Nikon tại Shinagawa. V&agrave;o cửa miễn ph&iacute;.</span><br />\r\n<br />\r\n<img alt=\"Nikon-Museum-4.\" class=\"LbImage bbCodeImage\" src=\"https://photo.tinhte.vn/store/2015/10/3155491_Nikon-Museum-4.jpg\" style=\"-webkit-text-stroke-width:0px; background-color:rgb(255, 255, 255); border:0px; color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px; font-style:normal; font-variant:normal; font-weight:normal; letter-spacing:normal; line-height:20.5333px; max-width:100%; orphans:auto; text-align:left; text-indent:0px; text-transform:none; white-space:normal; widows:1; word-spacing:0px\" /><br />\r\n<span style=\"background-color:rgb(255, 255, 255); color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px\">Bảo t&agrave;ng Nikon sẽ l&agrave; nơi đầu ti&ecirc;n trưng b&agrave;y to&agrave;n bộ qu&aacute; tr&igrave;nh lịch sử, c&aacute;c sản phẩm v&agrave; c&ocirc;ng nghệ của Nikon qua c&aacute;c thời kỳ.</span><br />\r\n&nbsp;</p>\r\n\r\n<div style=\"margin: 0px; padding: 0px; color: rgb(20, 20, 20); font-family: Helvetica,Arial,sans-serif; font-size: 14.6667px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 20.5333px; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; background-color: rgb(255, 255, 255); text-align: center;\"><img alt=\"Nikon-Museum-6.\" class=\"LbImage bbCodeImage\" src=\"https://photo.tinhte.vn/store/2015/10/3155493_Nikon-Museum-6.jpg\" style=\"border:0px; max-width:100%\" />​</div>\r\n\r\n<p style=\"text-align:center\"><span style=\"background-color:rgb(255, 255, 255); color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px\">Nikon (thời điểm đ&oacute; l&agrave; Nippon Kogaku K.K.) được th&agrave;nh lập v&agrave;o năm 1917, được biết đến l&agrave; một tập đo&agrave;n chuy&ecirc;n sản xuất v&agrave; b&aacute;n c&aacute;c dụng cụ quang học dựa tr&ecirc;n c&ocirc;ng nghệ quang-điện tử v&agrave; c&ocirc;ng nghệ ch&iacute;nh x&aacute;c tr&ecirc;n to&agrave;n thế giới. Mục đ&iacute;ch của Bảo t&agrave;ng Nikon l&agrave; để triển l&atilde;m c&aacute;c c&ocirc;ng nghệ v&agrave; truyền thống từ nền tảng của họ cũng như những s&aacute;ng kiến v&agrave; sự ph&aacute;t triển của Nikon.</span><br />\r\n&nbsp;</p>\r\n\r\n<div style=\"margin: 0px; padding: 0px; color: rgb(20, 20, 20); font-family: Helvetica,Arial,sans-serif; font-size: 14.6667px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 20.5333px; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; background-color: rgb(255, 255, 255); text-align: center;\"><img alt=\"Nikon-Museum.\" class=\"LbImage bbCodeImage\" src=\"https://photo.tinhte.vn/store/2015/10/3155496_Nikon-Museum.jpg\" style=\"border:0px; max-width:100%\" />​</div>\r\n\r\n<p style=\"text-align:center\"><span style=\"background-color:rgb(255, 255, 255); color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px\">Bảo t&agrave;ng Nikon sẽ bao gồm 580 m2 kh&ocirc;ng gian triển l&atilde;m, trưng b&agrave;y c&aacute;c sản phẩm c&oacute; gi&aacute; trị của Nikon như: hệ thống sản xuất b&aacute;n dẫn step&amp;repeat của Nikon &quot;NSR-1505G2A&rdquo; từ 1984, khoảng hơn 450 m&aacute;y ảnh Nikon từ đời &quot;Nikon đời 1&rdquo;, đ&acirc;y l&agrave; chiếc m&aacute;y ảnh Nikon đầu ti&ecirc;n được c&ocirc;ng bố v&agrave;o năm 1948, cho tới c&aacute;c m&aacute;y ảnh kỹ thuật số mới nhất, k&iacute;nh hiển vi, dụng cụ đo lường, v&agrave; c&aacute;c thiết bị hỗ trợ kh&aacute;c trong sự ph&aacute;t triển của Nikon.</span><br />\r\n&nbsp;</p>\r\n\r\n<div style=\"margin: 0px; padding: 0px; color: rgb(20, 20, 20); font-family: Helvetica,Arial,sans-serif; font-size: 14.6667px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 20.5333px; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; background-color: rgb(255, 255, 255); text-align: center;\"><img alt=\"Nikon-Museum-3.\" class=\"LbImage bbCodeImage\" src=\"https://photo.tinhte.vn/store/2015/10/3155489_Nikon-Museum-3.jpg\" style=\"border:0px; max-width:100%\" /><img alt=\"Nikon-Museum-7.\" class=\"LbImage bbCodeImage\" src=\"https://photo.tinhte.vn/store/2015/10/3155492_Nikon-Museum-7.jpg\" style=\"border:0px; max-width:100%\" />​</div>\r\n\r\n<p style=\"text-align:center\"><span style=\"background-color:rgb(255, 255, 255); color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px\">Trong cửa h&agrave;ng của bảo t&agrave;ng, c&oacute; sẵn c&aacute;c loại h&agrave;ng lưu niệm kh&aacute;c nhau cho kh&aacute;ch tham quan, bao gồm c&aacute;c phi&ecirc;n bản giới hạn của Bảo t&agrave;ng Nikon như bưu thiếp, khăn lau mặt kiểu Nhật, folder bằng nhựa trong, t&uacute;i đeo, &quot;Nikon Yokan&rdquo; với kiểu đ&oacute;ng g&oacute;i nguy&ecirc;n bản (một loại b&aacute;nh của Nhật Bản), v&agrave; c&aacute;c mặt h&agrave;ng kh&aacute;c thường thấy tr&ecirc;n c&aacute;c shop online của Nhật như những con lật đật phi&ecirc;n bản giới hạn.</span><br />\r\n<br />\r\n<span style=\"background-color:rgb(255, 255, 255); color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px\">Một số h&igrave;nh ảnh của bảo t&agrave;ng Nikon:</span><br />\r\n<img alt=\"Nikon-Museum-5.\" class=\"LbImage bbCodeImage\" src=\"https://photo.tinhte.vn/store/2015/10/3155490_Nikon-Museum-5.jpg\" style=\"-webkit-text-stroke-width:0px; background-color:rgb(255, 255, 255); border:0px; color:rgb(20, 20, 20); font-family:helvetica,arial,sans-serif; font-size:14.6667px; font-style:normal; font-variant:normal; font-weight:normal; letter-spacing:normal; line-height:20.5333px; max-width:100%; orphans:auto; text-align:left; text-indent:0px; text-transform:none; white-space:normal; widows:1; word-spacing:0px\" /></p>\r\n\r\n<div style=\"margin: 0px; padding: 0px; color: rgb(20, 20, 20); font-family: Helvetica,Arial,sans-serif; font-size: 14.6667px; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: 20.5333px; text-indent: 0px; text-transform: none; white-space: normal; widows: 1; word-spacing: 0px; background-color: rgb(255, 255, 255); text-align: center;\"><img alt=\"Nikon-Museum2.\" class=\"LbImage bbCodeImage\" src=\"https://photo.tinhte.vn/store/2015/10/3155505_Nikon-Museum2.jpg\" style=\"border:0px; max-width:100%\" /><br />\r\n<br />\r\n<em>Nikon sẽ mở cửa Bảo t&agrave;ng Nikon v&agrave;o ng&agrave;y 17 th&aacute;ng 10 năm 2015 tại Nhật nh&acirc;n kỷ niệm 100 năm th&agrave;nh lập Nikon</em>​</div>\r\n', 'Nikon sẽ mở cửa Bảo tàng Nikon tại Nhật nhân kỷ niệm 100 năm Nikon vào ngày 17/10/2015', 'Nikon sẽ mở cửa Bảo tàng Nikon tại Nhật nhân kỷ niệm 100 năm Nikon vào ngày 17/10/2015', 'Nikon sẽ mở cửa Bảo tàng Nikon tại Nhật nhân kỷ niệm 100 năm Nikon vào ngày 17/10/2015', 'Koala_10062015101939.jpg', 'show', 'admin', '0', '1', '1444119579', '1444646383', '1', '1');
INSERT INTO `cms_show` VALUES ('6', '1', 'Phương án thiết kế Khách sạn và căn hộ Oceanus Nha Trang', 'phuong-an-thiet-ke-khach-san-va-can-ho-oceanus-nha-trang', null, null, '{\"bpGallery\":\"561cd703ebb20.jpg\",\"bpLogo\":\"\",\"bpLocation\":\"21 Nguy\\u1ec5n Trung Ng\\u1ea1n\",\"bpType\":\"\",\"bpAcreage\":\"\",\"bpAcreageCenter\":\"\",\"bpApartmentNo\":\"\",\"bpFloorNo\":\"\",\"bpFacilities\":\"\",\"bpMapLocation\":\"\",\"bpMapLocationDes\":\"\",\"bpFacilitiesDetail\":\"\",\"bpFacilitiesDetailDes\":\"\",\"bpVideo\":\"\",\"bpProgress\":null,\"bpLat\":\"10.783233\",\"bpLng\":\"106.704479\",\"bpHotline\":\"\",\"bpWebsite\":\"\",\"bpStartTime\":\"\",\"bpEstimateFinished\":\"\",\"bpOwnerType\":\"\",\"bpfApartmentArea\":\"{\\\"floorPlan\\\":[],\\\"payment\\\":\\\"\\\",\\\"promotion\\\":\\\"\\\",\\\"document\\\":\\\"\\\"}\",\"bpfCommercialArea\":\"{\\\"floorPlan\\\":[],\\\"payment\\\":\\\"\\\",\\\"promotion\\\":\\\"\\\",\\\"document\\\":\\\"\\\"}\",\"bpfTownhouseArea\":\"{\\\"floorPlan\\\":[],\\\"payment\\\":\\\"\\\",\\\"promotion\\\":\\\"\\\",\\\"document\\\":\\\"\\\"}\",\"bpfOffice\":\"{\\\"floorPlan\\\":[],\\\"payment\\\":\\\"\\\",\\\"promotion\\\":\\\"\\\",\\\"document\\\":\\\"\\\"}\"}', '', '', '', null, 'show', 'admin', '0', '1', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for gallery_image
-- ----------------------------
DROP TABLE IF EXISTS `gallery_image`;
CREATE TABLE `gallery_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `ownerId` varchar(255) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of gallery_image
-- ----------------------------
INSERT INTO `gallery_image` VALUES ('1', 'product', '1', '2', 'kakak', '654654');
INSERT INTO `gallery_image` VALUES ('2', 'product', '1', '1', '', '');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', '1443752329');
INSERT INTO `migration` VALUES ('m140209_132017_init', '1443752345');
INSERT INTO `migration` VALUES ('m140403_174025_create_account_table', '1443752346');
INSERT INTO `migration` VALUES ('m140501_075311_add_oauth2_server', '1445329980');
INSERT INTO `migration` VALUES ('m140504_113157_update_tables', '1443752349');
INSERT INTO `migration` VALUES ('m140504_130429_create_token_table', '1443752350');
INSERT INTO `migration` VALUES ('m140506_102106_rbac_init', '1443752877');
INSERT INTO `migration` VALUES ('m140602_111327_create_menu_table', '1443752758');
INSERT INTO `migration` VALUES ('m140830_171933_fix_ip_field', '1443752351');
INSERT INTO `migration` VALUES ('m140830_172703_change_account_table_name', '1443752351');
INSERT INTO `migration` VALUES ('m140930_003227_gallery_manager', '1443774230');
INSERT INTO `migration` VALUES ('m141208_201488_setting_init', '1443753325');
INSERT INTO `migration` VALUES ('m141222_110026_update_ip_field', '1443752352');
INSERT INTO `migration` VALUES ('m141222_135246_alter_username_length', '1443752352');
INSERT INTO `migration` VALUES ('m150614_103145_update_social_account_table', '1443752354');
INSERT INTO `migration` VALUES ('m150623_212711_fix_username_notnull', '1443752355');
INSERT INTO `migration` VALUES ('m151002_024804_cms_show', '1444119260');
INSERT INTO `migration` VALUES ('m151002_081136_profile', '1444119315');

-- ----------------------------
-- Table structure for mv_meta
-- ----------------------------
DROP TABLE IF EXISTS `mv_meta`;
CREATE TABLE `mv_meta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL DEFAULT '/',
  `metadata` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mv_meta
-- ----------------------------
INSERT INTO `mv_meta` VALUES ('1', 'http://local.lancaster.com/news', '{\"keywords\":\"Metvuong News, metvuong, news \",\"description\":\"Là đội ngũ những người có cùng niềm đam mê và giàu kinh nghiệm trong lĩnh vực bất động sản. Chúng tôi mong muốn tạo ra một thứ gì đó thú vị để đóng góp vào sự phát triển của thị trường bất động sản Việt Nam.\",\"og:title\":\"Metvuong News\",\"og:image\":\"http://local.metvuong.com/store/news/show/GDP_10062015045857.jpg\",\"article:author\":\"tmnhut\",\"article:publisher\":\"admin\",\"og:description\":\"Là đội ngũ những người có cùng niềm đam mê và giàu kinh nghiệm trong lĩnh vực bất động sản. Chúng tôi mong muốn tạo ra một thứ gì đó thú vị để đóng góp vào sự phát triển của thị trường bất động sản Việt Nam.\",\"og:url\":\"http://local.lancaster.com/news\",\"fb:app_id\":\"856286731156793\",\"og:site_name\":\"Lancaster\",\"og:type\":\"article\",\"og:locale\":\"vi_VN\",\"og:locale:alternate\":\"en_US\"}');
INSERT INTO `mv_meta` VALUES ('2', 'http://local.metvuong.com/', '{\"keywords\":\"Metvuong, metvuong\",\"description\":\"Là đội ngũ những người có cùng niềm đam mê và giàu kinh nghiệm trong lĩnh vực bất động sản. Chúng tôi mong muốn tạo ra một thứ gì đó thú vị để đóng góp vào sự phát triển của thị trường bất động sản Việt Nam.\",\"og:title\":\"Metvuong\",\"og:image\":\"http://local.metvuong.com/store/news/show/GDP_10062015045857.jpg\",\"article:author\":\"tmnhut\",\"article:publisher\":\"admin\",\"og:description\":\"Là đội ngũ những người có cùng niềm đam mê và giàu kinh nghiệm trong lĩnh vực bất động sản. Chúng tôi mong muốn tạo ra một thứ gì đó thú vị để đóng góp vào sự phát triển của thị trường bất động sản Việt Nam.\",\"og:url\":\"http://local.metvuong.com/\",\"fb:app_id\":\"856286731156793\",\"og:site_name\":\"Lancaster\",\"og:type\":\"article\",\"og:locale\":\"vi_VN\",\"og:locale:alternate\":\"en_US\"}');

-- ----------------------------
-- Table structure for profile
-- ----------------------------
DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `public_email` varchar(255) DEFAULT NULL,
  `gravatar_email` varchar(255) DEFAULT NULL,
  `gravatar_id` varchar(32) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `bio` text,
  `avatar` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of profile
-- ----------------------------
INSERT INTO `profile` VALUES ('1', 'Nguyễn Quang Vinh', 'quangvinh@abc.com', '', 'd41d8cd98f00b204e9800998ecf8427e', '', 'http://local.mv.com', '', '561ce59f056fe.jpg');
INSERT INTO `profile` VALUES ('2', null, null, null, null, null, null, null, '');
INSERT INTO `profile` VALUES ('3', null, null, null, null, null, null, null, '');

-- ----------------------------
-- Table structure for session
-- ----------------------------
DROP TABLE IF EXISTS `session`;
CREATE TABLE `session` (
  `id` char(40) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of session
-- ----------------------------
INSERT INTO `session` VALUES ('1q4m78o7opd5ngl0378bbpgok6', '1445570062', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A373A222F61646D696E2F223B5F5F69647C693A313B);
INSERT INTO `session` VALUES ('4bp5pum7gf9so2rjgja4hdboa3', '1444039853', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('4v61d18aa89c39btp15njcg742', '1444280677', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('5c8bksi3gntr337ir0trglrp16', '1444039846', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('b6f1s07h2otimev198itioa8k0', '1444123047', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('blohurbv6vtqcodmuagqmuta42', '1445591194', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A373A222F61646D696E2F223B5F5F69647C693A313B);
INSERT INTO `session` VALUES ('ctmt3s71trkjh4lfnvf7t91o06', '1444725408', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A32393A222F61646D696E2F757365722F61646D696E2F6176617461723F69643D33223B5F5F69647C693A313B616374696F6E732D72656469726563747C733A32393A222F61646D696E2F757365722F61646D696E2F6176617461723F69643D33223B);
INSERT INTO `session` VALUES ('d93dcldbflftn34eru40bf2il6', '1444039963', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('g5q91noi0uj6e8nj9dhhdd1fj4', '1445914667', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A363A222F61646D696E223B5F5F69647C693A313B);
INSERT INTO `session` VALUES ('hs2eecuvrfisjetn6h97dag6n4', '1444280677', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A373A222F61646D696E2F223B);
INSERT INTO `session` VALUES ('j170kn4ucu72hrso81p9uop2m2', '1444123053', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('la3dk7ivs2bgu3cs3n75k7qrj0', '1444619371', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('ljgtbet7thvn9vlbb4ehq9kpp3', '1445998213', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A363A222F61646D696E223B);
INSERT INTO `session` VALUES ('nkvpg1kv5oqrsree8av0aintf7', '1445239065', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('pbbrlol5tti21bsstbrss1gvl7', '1445998213', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('qb6afen96lb5mk1bcu9t0gn3r7', '1446004710', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A373A222F61646D696E2F223B5F5F69647C693A313B);
INSERT INTO `session` VALUES ('t2i9rnnk56e7s3or7bmg8ej367', '1444619370', 0x5F5F666C6173687C613A303A7B7D5F5F72657475726E55726C7C733A363A222F61646D696E223B);
INSERT INTO `session` VALUES ('tisiqk0nes1olblo34pagctia5', '1444039195', 0x5F5F666C6173687C613A303A7B7D);
INSERT INTO `session` VALUES ('uqs2kkkqado15r0nlt48o0j772', '1444039033', 0x5F5F666C6173687C613A303A7B7D);

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(32) NOT NULL,
  `type` varchar(32) NOT NULL,
  `store_range` varchar(255) DEFAULT NULL,
  `store_dir` varchar(255) DEFAULT NULL,
  `value` text,
  `sort_order` int(11) NOT NULL DEFAULT '50',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `code` (`code`),
  KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=3116 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES ('11', '0', 'info', 'group', '', '', '', '50');
INSERT INTO `setting` VALUES ('21', '0', 'basic', 'group', '', '', '', '50');
INSERT INTO `setting` VALUES ('31', '0', 'smtp', 'group', '', '', '', '50');
INSERT INTO `setting` VALUES ('1111', '11', 'siteName', 'text', '', '', 'Your Site', '50');
INSERT INTO `setting` VALUES ('1112', '11', 'siteTitle', 'text', '', '', 'Your Site Title', '50');
INSERT INTO `setting` VALUES ('1113', '11', 'siteKeyword', 'text', '', '', 'Your Site Keyword', '50');
INSERT INTO `setting` VALUES ('2111', '21', 'timezone', 'select', '-12,-11,-10,-9,-8,-7,-6,-5,-4,-3.5,-3,-2,-1,0,1,2,3,3.5,4,4.5,5,5.5,5.75,6,6.5,7,8,9,9.5,10,11,12', '', '8', '50');
INSERT INTO `setting` VALUES ('2112', '21', 'commentCheck', 'select', '0,1', '', '1', '50');
INSERT INTO `setting` VALUES ('3111', '31', 'smtpHost', 'text', '', '', 'localhost', '50');
INSERT INTO `setting` VALUES ('3112', '31', 'smtpPort', 'text', '', '', '', '50');
INSERT INTO `setting` VALUES ('3113', '31', 'smtpUser', 'text', '', '', '', '50');
INSERT INTO `setting` VALUES ('3114', '31', 'smtpPassword', 'password', '', '', '', '50');
INSERT INTO `setting` VALUES ('3115', '31', 'smtpMail', 'text', '', '', '', '50');

-- ----------------------------
-- Table structure for social_account
-- ----------------------------
DROP TABLE IF EXISTS `social_account`;
CREATE TABLE `social_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `provider` varchar(255) NOT NULL,
  `client_id` varchar(255) NOT NULL,
  `data` text,
  `code` varchar(32) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_unique` (`provider`,`client_id`),
  UNIQUE KEY `account_unique_code` (`code`),
  KEY `fk_user_account` (`user_id`),
  CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of social_account
-- ----------------------------
INSERT INTO `social_account` VALUES ('1', '2', 'facebook', '100003885175023', '{\"name\":\"Anh Map\",\"email\":\"quangvinhit2010@gmail.com\",\"id\":\"100003885175023\"}', null, null, null, null);
INSERT INTO `social_account` VALUES ('2', '3', 'facebook', '1068833803128500', '{\"name\":\"Nhut Tran\",\"email\":\"nhut.love@gmail.com\",\"id\":\"1068833803128500\"}', null, null, null, null);

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token` (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  UNIQUE KEY `token_unique` (`user_id`,`code`,`type`),
  CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of token
-- ----------------------------
INSERT INTO `token` VALUES ('1', 'EX3f_-rFGty2JvcQr5P-YW0wWe0Vw2Su', '1443753609', '0');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(60) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `confirmed_at` int(11) DEFAULT NULL,
  `unconfirmed_email` varchar(255) DEFAULT NULL,
  `blocked_at` int(11) DEFAULT NULL,
  `registration_ip` varchar(45) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_unique_email` (`email`),
  UNIQUE KEY `user_unique_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'superadmin', 'quangvinh.nguyen@trungthuygroup.vn', '$2y$12$dOo38UxtuownHx4aXmnCC.EKu4ZbPu6UBR6ljcPTQGMn5i5nIw1VG', 'vD8-Xl41YKbPJHUHMhIRKbAE7QTh1VXk', '1443753608', null, null, '127.0.0.1', '1443753608', '1443753680', '0');
INSERT INTO `user` VALUES ('2', 'quangvinhit2010', 'quangvinhit2010@gmail.com', '$2y$12$b5zdpeMTVQiaCf./piPOC.iYwwQJSpQnI5vsSB7EHA0GSFVDvCta6', 'ixZhHBjcvw-U9OqLNz7Z4a9-J4_C_--P', '1444038310', null, null, '127.0.0.1', '1444038310', '1444038310', '0');
INSERT INTO `user` VALUES ('3', 'nhuttran', 'nhut.tran@trungthuygroup.vn', '$2y$12$79YPtcSQaoZS6/O1lG5.EuYBtK0SZXDSwj7YPnzAGN11/VVqkY8eC', 'pcLqflnXuVmWbn1LdPqhoOPr_LZlgGof', '1444038490', null, null, '127.0.0.1', '1444038490', '1444038490', '0');
