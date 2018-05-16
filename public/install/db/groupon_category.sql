/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-10-22 17:06:40
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for groupon_category
-- ----------------------------
DROP TABLE IF EXISTS `groupon_category`;
CREATE TABLE `groupon_category` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL COMMENT '标签名称',
  `parent_id` int(11) DEFAULT NULL COMMENT '上级分类 ID',
  `hid` varchar(255) DEFAULT NULL COMMENT '分类层级关系以:分隔',
  `sort_order` int(11) DEFAULT '0' COMMENT '排序',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='团购标签';
