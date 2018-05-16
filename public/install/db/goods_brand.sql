/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:48:26
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_brand`
-- ----------------------------
DROP TABLE IF EXISTS `goods_brand`;
CREATE TABLE `goods_brand` (
  `brand_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(50) NOT NULL COMMENT '品牌名称',
  `brand_name_en` varchar(50) DEFAULT NULL COMMENT '商品品牌英文名',
  `brand_url` varchar(255) DEFAULT NULL COMMENT '品牌官网地址',
  `brand_desc` text COMMENT '品牌介绍',
  `brand_logo` varchar(255) DEFAULT NULL COMMENT '品牌标志地址',
  `brand_keywords` text COMMENT '品牌关键字。多个以竖线“|”进行分隔',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否启用。1：是；0：否；',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  `mtime` datetime NOT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`brand_id`),
  KEY `ind_disabled` (`status`) USING BTREE,
  KEY `ind_ordernum` (`sort_order`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品品牌表';

-- ----------------------------
-- Records of goods_brand
-- ----------------------------
