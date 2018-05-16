# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 10.58.128.61 (MySQL 5.5.27-log)
# Database: bm_yumzeed
# Generation Time: 2015-10-15 07:40:30 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cas_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cas_user`;

CREATE TABLE `cas_user` (
  `userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_group_id` smallint(5) DEFAULT '0' COMMENT '用户所属组别 ID',
  `user_lv_id` smallint(5) DEFAULT '0' COMMENT '用户所属等级 ID',
  `username` varchar(200) DEFAULT NULL COMMENT '用户名',
  `password` varchar(200) DEFAULT NULL COMMENT '密码',
  `freeze` varchar(200) DEFAULT NULL COMMENT '冻结器密码',
  `thirdid` varchar(200) DEFAULT NULL COMMENT '第三方平台ID',
  `salt` varchar(20) DEFAULT NULL COMMENT 'password enhanced vars',
  `encrypt` varchar(20) DEFAULT NULL COMMENT 'password encrypt handle or function',
  `tel` varchar(16) DEFAULT NULL COMMENT '座机号码',
  `phone` varchar(11) DEFAULT NULL COMMENT '用户手机号',
  `verifiedPhone` tinyint(1) NOT NULL DEFAULT '0' COMMENT '手机验证状态。1：验证通过；0：验证未通过；',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `verifiedEmail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮箱验证状态。1：验证通过；0：验证未通过；',
  `nickname` varchar(20) DEFAULT NULL COMMENT '昵称，默认同用户名 username',
  `realname` varchar(20) NOT NULL COMMENT '真实姓名',
  `idcard` char(18) NOT NULL COMMENT '身份证号，18位',
  `gender` tinyint(1) DEFAULT '0' COMMENT '性别。1：男；2：女；0：未知；',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `device_type` varchar(50) DEFAULT NULL COMMENT '设备类型。android、iphone、ipad',
  `device_no` text COMMENT '设备序列号',
  `channelid` varchar(50) DEFAULT NULL COMMENT '通道 ID（适用于安卓推送）',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户状态。1：已激活；0：未激活；',
  `is_verify` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核。1：是；0：否；',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否标记为删除。1：是；0：否；',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登陆时间',
  `last_login_ip` char(15) DEFAULT NULL COMMENT '最后登陆ip',
  `ban_etime` datetime DEFAULT NULL COMMENT '帐号封停结束时间',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  `mtime` datetime DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `thirdid` (`thirdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
