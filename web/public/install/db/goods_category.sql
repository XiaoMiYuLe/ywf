/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:48:35
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_category`
-- ----------------------------
DROP TABLE IF EXISTS `goods_category`;
CREATE TABLE `goods_category` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) DEFAULT NULL COMMENT '父级ID',
  `hid` varchar(255) DEFAULT NULL COMMENT '分类节点路径，以冒号进行分隔，比如：0:1:24',
  `category_name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `ad_id` int(11) NOT NULL COMMENT '广告ID',
  `description` text COMMENT '介绍',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否启用。1：是；0：否；',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  `mtime` datetime NOT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`category_id`),
  KEY `ind_cat_path` (`hid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品分类表';

-- ----------------------------
-- Records of goods_category
-- ----------------------------
