/*!40101 SET NAMES utf8 */;

/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : bm_yumzeed_zr

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-11-05 11:02:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for coupon_listing
-- ----------------------------
DROP TABLE IF EXISTS `coupon_listing`;
CREATE TABLE `coupon_listing` (
  `cpns_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `coupon_id` int(10) DEFAULT NULL COMMENT '优惠券id，关联表：coupon_category',
  `cpns_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠券状态。未使用：0；已使用：1；异常：-1；',
  `is_del` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否标记删除 0未删除 1已删除',
  `disabled` tinyint(1) DEFAULT '0' COMMENT '是否失效 1:失效 0:未失效',
  `userid` int(10) DEFAULT '0' COMMENT '用户ID',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  `mtime` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`cpns_id`),
  KEY `ind_disabled` (`disabled`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
