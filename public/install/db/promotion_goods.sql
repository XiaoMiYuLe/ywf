/*!40101 SET NAMES utf8 */;
/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-10-23 10:48:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for promotion_goods
-- ----------------------------
DROP TABLE IF EXISTS `promotion_goods`;
CREATE TABLE `promotion_goods` (
  `promotion_id` int(11) unsigned DEFAULT NULL COMMENT '优惠促销活动 ID。对应表：promotion_content',
  `content_id` varchar(32) DEFAULT '' COMMENT '关联商品id。对应表：goods_content',
  `goods_category_id` int(10) DEFAULT '0' COMMENT '关联商品分类 ID。对应表：goods_category',
  `goods_brand_id` int(10) DEFAULT '0' COMMENT '关联商品品牌 ID。对应表：goods_brand'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠促销活动所关联的商品表';
