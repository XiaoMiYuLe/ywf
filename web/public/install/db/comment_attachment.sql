/*!40101 SET NAMES utf8 */; 
/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-10-22 16:05:19
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for comment_attachment
-- ----------------------------
DROP TABLE IF EXISTS `comment_attachment`;
CREATE TABLE `comment_attachment` (
  `comment_id` int(11) NOT NULL DEFAULT '0' COMMENT '评论 ID',
  `attachmentid` int(11) NOT NULL DEFAULT '0' COMMENT '附件 ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '上传的用户 ID',
  `type` enum('share_order') NOT NULL DEFAULT 'share_order' COMMENT '附件类型。share_order：晒单；',
  PRIMARY KEY (`comment_id`,`attachmentid`),
  KEY `attachmentid` (`attachmentid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论附件关系表';
