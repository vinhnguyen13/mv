/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.6.17-log : Database - metvuong
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `ad_category` */

DROP TABLE IF EXISTS `ad_category`;

CREATE TABLE `ad_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `apply_to_type` tinyint(1) NOT NULL COMMENT '1: nhà đất bán, 2: nhà đất cho thuê; 3: cả hai',
  `order` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1' COMMENT '1: actived, 0: deactived',
  `template` tinyint(1) NOT NULL DEFAULT '1',
  `limit_area` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `ad_category` */

insert  into `ad_category`(`id`,`name`,`apply_to_type`,`order`,`status`,`template`,`limit_area`) values (6,'căn hộ chung cư',1,0,1,1,200),(7,'nhà riêng',3,1,1,2,200),(8,'nhà biệt thự, liền kề',3,2,1,2,200),(9,'nhà mặt phố',3,3,1,2,200),(10,'đất nền dự án',1,4,1,2,NULL),(11,'đất',1,5,0,1,NULL),(12,'trang trại, khu nghỉ dưỡng',1,6,0,1,NULL),(13,'kho, nhà xưởng',1,7,0,1,NULL),(14,'loại bất động sản khác',1,12,0,1,NULL),(15,'nhà trọ, phòng trọ',2,8,0,1,NULL),(16,'văn phòng',2,9,0,1,NULL),(17,'cửa hàng, ki ốt',2,10,0,1,NULL),(18,'kho, nhà xưởng, đất',2,11,0,1,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
