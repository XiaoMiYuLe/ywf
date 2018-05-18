# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.23)
# Database: bm_yumzeed_sjq
# Generation Time: 2015-10-23 09:17:59 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table store_content
# ------------------------------------------------------------

DROP TABLE IF EXISTS `store_content`;

CREATE TABLE `store_content` (
  `store_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) DEFAULT '0' COMMENT '用户 ID。关联表：cas_user',
  `store_name` varchar(200) NOT NULL DEFAULT '' COMMENT '店铺名称',
  `company_name` varchar(200) NOT NULL COMMENT '公司名称',
  `logo` varchar(255) DEFAULT NULL COMMENT '店铺logo图片',
  `setup_date` date DEFAULT NULL COMMENT '公司成立日期',
  `register_capital` decimal(10,2) DEFAULT '0.00' COMMENT '公司注册资本',
  `employee_nums` smallint(5) DEFAULT '0' COMMENT '公司员工数',
  `region_id` int(11) DEFAULT NULL COMMENT '所在地区 ID',
  `region_name` varchar(100) DEFAULT NULL COMMENT '地区名称。如：中国 湖北省 武汉市',
  `address` varchar(200) DEFAULT NULL COMMENT '详细地址',
  `region_id_ship` int(11) DEFAULT NULL COMMENT '发货地区 ID',
  `address_return` varchar(200) DEFAULT NULL COMMENT '退货收货地址',
  `return_rule` text COMMENT '退货条约',
  `business_time` varchar(255) DEFAULT NULL COMMENT '营业时间',
  `run_category` varchar(255) DEFAULT NULL COMMENT '经营类别',
  `tel` varchar(50) DEFAULT NULL COMMENT '公司电话',
  `fax` varchar(50) DEFAULT NULL COMMENT '传真号码',
  `homepage` varchar(100) DEFAULT NULL COMMENT '公司主页',
  `email` varchar(100) DEFAULT NULL COMMENT '电子邮箱',
  `image_default` varchar(255) DEFAULT NULL COMMENT '店铺主页图片',
  `business_license` varchar(50) DEFAULT NULL COMMENT '营业执照号码',
  `business_image` varchar(255) DEFAULT NULL COMMENT '营业执照图片',
  `business_categoryids` varchar(255) DEFAULT NULL COMMENT '主营业务 ',
  `legalp_name` varchar(20) DEFAULT NULL COMMENT '法人姓名',
  `description` varchar(255) DEFAULT NULL COMMENT '简单描述',
  `is_official` tinyint(1) DEFAULT '0' COMMENT '是否标记为官方店铺。1：是；0：否；',
  `source` enum('local','self') DEFAULT 'local' COMMENT '商户来源',
  `is_signing` tinyint(1) DEFAULT '0' COMMENT '是否签约 1：是；0：否；',
  `signing_time_start` datetime DEFAULT NULL COMMENT '签约开始时间',
  `signing_time_end` datetime DEFAULT NULL COMMENT '签约到期时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态。0：未启用； 1：正常； -1：删除',
  `is_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否通过审核。1：审核通过；0：待审核；-1：审核未通过（即驳回）',
  `rejection_reason` text COMMENT '驳回理由',
  `creator_userid` int(11) DEFAULT '0' COMMENT '创建者 ID。对应表：admin_user',
  `goods_verify` tinyint(1) DEFAULT '1' COMMENT '发布商品需要平台审核。1：需要；0：不需要；',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  `mtime` datetime DEFAULT NULL COMMENT '最后一次更新时间',
  `verify_time` datetime DEFAULT NULL COMMENT '审核通过时间',
  PRIMARY KEY (`store_id`),
  KEY `userid` (`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='已签约商户表';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
