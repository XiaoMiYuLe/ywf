# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.23)
# Database: bm_yumzeed_sjq
# Generation Time: 2015-10-23 09:19:34 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table store_content_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `store_content_log`;

CREATE TABLE `store_content_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单日志ID',
  `store_id` int(11) DEFAULT NULL COMMENT '店铺ID',
  `opreator_type` tinyint(1) DEFAULT '1' COMMENT '操作者类型 1 :后台管理员 2: 商户自身 ',
  `opreator_id` int(11) DEFAULT NULL COMMENT '操作员ID',
  `operator_name` varchar(200) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL COMMENT '操作内容',
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  `ip` varchar(30) DEFAULT NULL COMMENT '操作人IP',
  `ctime` datetime DEFAULT NULL COMMENT '创建数据日期',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单操作日志表';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
