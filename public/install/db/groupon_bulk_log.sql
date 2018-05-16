/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-10-22 17:06:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for groupon_bulk_log
-- ----------------------------
DROP TABLE IF EXISTS `groupon_bulk_log`;
CREATE TABLE `groupon_bulk_log` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '团购商品 ID',
  `bulk_id` int(11) DEFAULT NULL COMMENT '团购 ID',
  `sku` varchar(255) DEFAULT NULL COMMENT '团购商品ID',
  `goods_name` varchar(255) DEFAULT NULL COMMENT '团购商品名称',
  `userid` int(11) DEFAULT NULL COMMENT '团购用户 ID',
  `number` int(11) DEFAULT NULL COMMENT '团购个数',
  `price` float(11,2) DEFAULT NULL COMMENT '团购价格',
  `integral` int(11) DEFAULT '0' COMMENT '团购使用积分',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='团购日志表';
