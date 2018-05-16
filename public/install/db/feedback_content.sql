/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-16 11:48:37
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `feedback_content`
-- ----------------------------

CREATE TABLE `feedback_content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL COMMENT '留言标题',
  `body` text COMMENT '留言详情',
  `reply` varchar(255) DEFAULT NULL COMMENT '回复',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT ' 若发布者为登录会员，则记录会员 ID',
  `ip` char(15) DEFAULT NULL COMMENT '留言者 IP',
  `ctime` datetime NOT NULL COMMENT '记录创建时间',
  `reply_time` datetime DEFAULT NULL COMMENT '回复时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已处理。1：是；0：否；',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言反馈表';

-- ----------------------------
-- Records of feedback_content
-- ----------------------------
