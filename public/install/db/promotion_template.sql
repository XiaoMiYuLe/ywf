/*!40101 SET NAMES utf8 */;
/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-10-23 10:48:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for promotion_template
-- ----------------------------
DROP TABLE IF EXISTS `promotion_template`;
CREATE TABLE `promotion_template` (
  `template_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) NOT NULL DEFAULT '' COMMENT '模板标题',
  `content` text COMMENT '模板说明',
  `filepath` varchar(255) DEFAULT NULL COMMENT '模板文件存放位置',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否标记为删除状态。1：是；0：否；',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  `mtime` datetime DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠促销活动模板';
