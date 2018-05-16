/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:48:21
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_attachment`
-- ----------------------------
DROP TABLE IF EXISTS `goods_attachment`;
CREATE TABLE `goods_attachment` (
  `goods_attachment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '商品 ID',
  `attachmentid` bigint(20) NOT NULL DEFAULT '0' COMMENT '附件 ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '上传的用户 ID，即后台管理员的 userid',
  `type` enum('image') NOT NULL DEFAULT 'image' COMMENT '附件类型。image：图片；',
  PRIMARY KEY (`goods_attachment_id`),
  KEY `userid_attachment_type` (`content_id`,`userid`,`type`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品附件关系表';

-- ----------------------------
-- Records of goods_attachment
-- ----------------------------
