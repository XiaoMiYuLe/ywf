/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:50:04
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `trend_property_value`
-- ----------------------------
DROP TABLE IF EXISTS `trend_property_value`;
CREATE TABLE `trend_property_value` (
  `property_value_id` int(11) NOT NULL AUTO_INCREMENT,
  `property_id` int(11) NOT NULL COMMENT '对应的属性 ID',
  `property_value` varchar(255) NOT NULL COMMENT '参数可选值',
  `property_image` varchar(255) DEFAULT NULL COMMENT '规格图片',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号',
  `is_default` tinyint(1) DEFAULT '0' COMMENT '是否设置为默认值。1：是；0：否；',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '(暂时不用)状态。1：启用；0：不启用；',
  PRIMARY KEY (`property_value_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='属性资源值表';

-- ----------------------------
-- Records of trend_property_value
-- ----------------------------
