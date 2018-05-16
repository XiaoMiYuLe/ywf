/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:50:00
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `trend_property_to_group`
-- ----------------------------
DROP TABLE IF EXISTS `trend_property_to_group`;
CREATE TABLE `trend_property_to_group` (
  `property_id` int(11) NOT NULL,
  `property_group_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性分组 ID',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号',
  PRIMARY KEY (`property_id`,`property_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品属性与分组关系表';

-- ----------------------------
-- Records of trend_property_to_group
-- ----------------------------
