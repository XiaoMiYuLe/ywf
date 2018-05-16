/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:49:56
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `trend_property_group`
-- ----------------------------
DROP TABLE IF EXISTS `trend_property_group`;
CREATE TABLE `trend_property_group` (
  `property_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_group_name` varchar(30) NOT NULL COMMENT '分组名称',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号',
  PRIMARY KEY (`property_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='属性资源分组表';

-- ----------------------------
-- Records of trend_property_group
-- ----------------------------
