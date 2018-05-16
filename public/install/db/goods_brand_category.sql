/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:48:31
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_brand_category`
-- ----------------------------
DROP TABLE IF EXISTS `goods_brand_category`;
CREATE TABLE `goods_brand_category` (
  `brand_id` int(11) NOT NULL COMMENT '品牌 ID。关联表：goods_brand',
  `category_id` int(11) NOT NULL COMMENT '商品分类 ID。关联表：goods_category',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号',
  PRIMARY KEY (`brand_id`,`category_id`),
  KEY `brand_category` (`brand_id`,`category_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='品牌与分类关系表';

-- ----------------------------
-- Records of goods_brand_category
-- ----------------------------
