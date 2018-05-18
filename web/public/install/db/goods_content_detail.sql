/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:48:44
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_content_detail`
-- ----------------------------
DROP TABLE IF EXISTS `goods_content_detail`;
CREATE TABLE `goods_content_detail` (
  `content_id` bigint(20) NOT NULL,
  `memo` varchar(255) DEFAULT NULL COMMENT '商品简介',
  `body` longtext COMMENT '商品详情',
  `meta_title` varchar(255) DEFAULT NULL COMMENT '页面标题',
  `meta_keywords` varchar(255) DEFAULT NULL COMMENT '页面关键词',
  `meta_description` varchar(255) DEFAULT NULL COMMENT '页面描述',
  `tag` varchar(255) DEFAULT NULL COMMENT '商品标签 ID，多个以半角逗号分隔',
  `property` varchar(255) DEFAULT NULL COMMENT '商品扩展属性，多个以半角逗号分隔。记录格式为：属性 ID_属性值 ID',
  `spec` varchar(255) DEFAULT NULL COMMENT '已选用商品规格所对应属性 ID，多个以半角逗号分隔',
  `attachment` varchar(255) DEFAULT NULL COMMENT '商品图片 ID，多个以半角逗号分隔',
  `related` varchar(255) DEFAULT NULL COMMENT '关联商品 ID，多个以半角逗号分隔',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT=' 商品详细内容，包括商品详情、meta 相关字段等';

-- ----------------------------
-- Records of goods_content_detail
-- ----------------------------
