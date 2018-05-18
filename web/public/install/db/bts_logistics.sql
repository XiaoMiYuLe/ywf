/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-11-10 17:13:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bts_logistics
-- ----------------------------
DROP TABLE IF EXISTS `bts_logistics`;
CREATE TABLE `bts_logistics` (
  `logistics_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL COMMENT '物流名称',
  `company_name` varchar(100) DEFAULT NULL COMMENT '公司全称',
  `content` text COMMENT '公司详情',
  `tel` varchar(20) DEFAULT NULL COMMENT '联系电话',
  PRIMARY KEY (`logistics_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='物流主体表';
