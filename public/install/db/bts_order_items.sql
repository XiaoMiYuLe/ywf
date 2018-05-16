/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-11-10 17:13:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bts_order_items
-- ----------------------------
DROP TABLE IF EXISTS `bts_order_items`;
CREATE TABLE `bts_order_items` (
  `item_id` bigint(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `order_id` bigint(20) NOT NULL COMMENT '订单 ID',
  `content_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `sku` varchar(100) DEFAULT NULL COMMENT '商品编号',
  `goods_name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `goods_image` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `goods_weight` varchar(50) DEFAULT NULL COMMENT '重量',
  `goods_length` varchar(20) DEFAULT NULL COMMENT '长',
  `goods_wide` varchar(20) DEFAULT NULL COMMENT '宽',
  `goods_height` varchar(20) DEFAULT NULL COMMENT '高',
  `description` text COMMENT '商品详细信息（以 json 格式进行存储）',
  `buy_price` decimal(15,4) DEFAULT NULL COMMENT '购买价格',
  `buy_num` smallint(6) DEFAULT NULL COMMENT '购买数量',
  `is_comment` tinyint(1) DEFAULT '0' COMMENT '是否已经评价 0.否 1.是 2.已失效',
  `is_package` tinyint(1) DEFAULT '0' COMMENT '是否需要包装。1：是；0：否；',
  `ctime` datetime DEFAULT NULL,
  PRIMARY KEY (`item_id`,`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='订单的商品表(即订单详情表)';
