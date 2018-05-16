# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 10.58.128.61 (MySQL 5.5.27-log)
# Database: bm_yumzeed
# Generation Time: 2015-10-15 07:38:44 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table cas_code
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cas_code`;

CREATE TABLE `cas_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) DEFAULT '0' COMMENT '用户 ID，关联表：cas_user',
  `type` enum('email','phone') DEFAULT NULL COMMENT 'email：邮箱；phone：手机；',
  `action` enum('register','login','bind','forgotpassword') DEFAULT NULL COMMENT 'register：注册；login：登录；bind：绑定（邮箱、或手机）；forgotpassowrd：忘记密码；',
  `sendto` varchar(64) DEFAULT NULL COMMENT '发送至：手机号或 email',
  `code` varchar(64) DEFAULT NULL COMMENT '码',
  `ctime` datetime DEFAULT NULL COMMENT '发送时间',
  `exptime` datetime DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用于用户各种动作的码表';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
