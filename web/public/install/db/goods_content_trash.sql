/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:48:56
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_content_trash`
-- ----------------------------
DROP TABLE IF EXISTS `goods_content_trash`;
CREATE TABLE `goods_content_trash` (
  `content_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(128) NOT NULL DEFAULT '0' COMMENT '分类 ID',
  `brand_id` mediumint(8) unsigned DEFAULT NULL COMMENT '品牌 ID',
  `userid` int(11) DEFAULT '0' COMMENT '商品发布者的用户 ID。对应表：admin_user',
  `name` varchar(200) NOT NULL COMMENT '商品名称',
  `sku` varchar(30) NOT NULL COMMENT '商品sku',
  `image_default` varchar(255) DEFAULT NULL COMMENT '默认图片，即封面',
  `stock` mediumint(8) DEFAULT '0' COMMENT '库存',
  `weight` varchar(50) DEFAULT NULL COMMENT '重量。单位：克',
  `price` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '销售价格',
  `price_market` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '市场价格，即参考价格',
  `price_cost` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '成本价格。只在后台查看，前台不显示',
  `data` text COMMENT '商品其他信息的序列化存储',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否标记为删除（用于商户彻底删除时的操作）（暂不启用）。1：是；0：否；',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品回收站（暂不启用）';

-- ----------------------------
-- Records of goods_content_trash
-- ----------------------------
