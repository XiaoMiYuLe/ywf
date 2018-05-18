# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 10.58.128.61 (MySQL 5.5.27-log)
# Database: bm_yumzeed
# Generation Time: 2015-10-15 07:40:48 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cas_user_detail
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cas_user_detail`;

CREATE TABLE `cas_user_detail` (
  `userid` bigint(20) NOT NULL COMMENT '禁止联合查询',
  `birthday` datetime NOT NULL COMMENT '用户生日，即出生日期',
  `job` varchar(50) DEFAULT NULL COMMENT '职业',
  `signature` varchar(200) DEFAULT NULL COMMENT '个性签名',
  `region_id` int(11) unsigned NOT NULL COMMENT '地区 ID',
  `region_name` varchar(100) NOT NULL COMMENT '地区名称。如：中国 湖北省 武汉市',
  `address` varchar(255) DEFAULT NULL COMMENT '详细地址',
  `zipcode` varchar(20) DEFAULT NULL COMMENT '邮政编码',
  `source` enum('local','mobile','qzone','alipayquick','taobao','sina','baidu','renren','tencent','netease163','windowslive','yahoo','sohu') DEFAULT 'local' COMMENT '注册来源',
  `qa` text COMMENT '密保问答，以序列化方式存储',
  `user_sn` varchar(10) NOT NULL COMMENT '用户编号',
  `company_name` varchar(100) NOT NULL COMMENT '公司名称',
  `remark_type` varchar(2) NOT NULL DEFAULT 'b1' COMMENT '备注类型',
  `remark` text COMMENT '备注',
  `invitation_code` varchar(20) DEFAULT NULL COMMENT '邀请码',
  `longitude` varchar(18) DEFAULT NULL COMMENT '经度',
  `latitude` varchar(18) DEFAULT '' COMMENT '纬度',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户不常用详细信息表,禁止联合查询';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
