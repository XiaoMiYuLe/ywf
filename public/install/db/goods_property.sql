/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:49:29
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_property`
-- ----------------------------
DROP TABLE IF EXISTS `goods_property`;
CREATE TABLE `goods_property` (
  `goods_property_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `content_id` bigint(20) NOT NULL COMMENT '所属商品 ID。对应表：goods_content',
  `property_id` int(11) NOT NULL COMMENT '所属属性 ID。对应表：trend_property',
  `property_value_id` int(11) NOT NULL DEFAULT '0' COMMENT '属性值 ID。对应表：trend_property_value',
  PRIMARY KEY (`goods_property_id`,`content_id`,`property_id`,`property_value_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品属性表，用以记录商品属性值';

-- ----------------------------
-- Records of goods_property
-- ----------------------------
