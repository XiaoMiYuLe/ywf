/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:49:49
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `trend_property`
-- ----------------------------
DROP TABLE IF EXISTS `trend_property`;
CREATE TABLE `trend_property` (
  `property_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '参数名',
  `val_inputtype` enum('text','select','checkbox','radio','textarea','file') NOT NULL COMMENT '参数值输入标签类型',
  `label_name` varchar(50) NOT NULL COMMENT '标签名称',
  `placeholder` varchar(100) DEFAULT NULL COMMENT '输入框内部的提示文字',
  `note` varchar(200) NOT NULL COMMENT '参数说明',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号',
  `is_spec` tinyint(1) DEFAULT '0' COMMENT '是否开启规格（开启后将和价格、库存等相关联）。1：是；0：否；',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态。1：启用；0：不启用；',
  PRIMARY KEY (`property_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='属性资源表';

-- ----------------------------
-- Records of trend_property
-- ----------------------------
