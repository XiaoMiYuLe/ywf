/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:49:38
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_related`
-- ----------------------------
DROP TABLE IF EXISTS `goods_related`;
CREATE TABLE `goods_related` (
  `content_id` bigint(20) unsigned NOT NULL COMMENT '原商品 ID',
  `related_content_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '关联商品 ID',
  `type` varchar(100) DEFAULT 'goods' COMMENT '关联商品：goods; 非商品：related',
  PRIMARY KEY (`content_id`,`related_content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关联商品表';

-- ----------------------------
-- Records of goods_related
-- ----------------------------
