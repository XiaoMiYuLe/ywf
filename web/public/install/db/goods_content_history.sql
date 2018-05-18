/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:48:50
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_content_history`
-- ----------------------------
DROP TABLE IF EXISTS `goods_content_history`;
CREATE TABLE `goods_content_history` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `content_id` bigint(20) NOT NULL COMMENT '商品 ID',
  `rev` bigint(20) NOT NULL COMMENT '商品版本号',
  `name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `data` longtext COMMENT '商品所有信息的序列化存储',
  `userid` bigint(20) DEFAULT '0' COMMENT '最后一次编辑者 ID。关联表： admin_user；（当前用户编辑之后的内容实际对应的版本号是 rev + 1）',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品内容的历史版本记录';

-- ----------------------------
-- Records of goods_content_history
-- ----------------------------
