/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:49:42
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_stat`
-- ----------------------------
DROP TABLE IF EXISTS `goods_stat`;
CREATE TABLE `goods_stat` (
  `stat_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('view','agree','comment') DEFAULT 'view' COMMENT '操作类型。view：查看；agree：赞；comment：评论；',
  `userid` bigint(20) DEFAULT '0' COMMENT '操作会员的 ID，对应表：cas_user',
  `ip` char(15) DEFAULT NULL COMMENT '操作者 IP',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`stat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品统计表。比如：点击总数、评论总数等';

-- ----------------------------
-- Records of goods_stat
-- ----------------------------
