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

Date: 2015-10-14 17:36:58
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for advert_board
-- ----------------------------
DROP TABLE IF EXISTS `advert_board`;
CREATE TABLE `advert_board` (
  `board_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告位ID，主键',
  `page_id` int(11) DEFAULT NULL COMMENT '所属页面 ID',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '广告类型。1：图片；2：文字；3：flash；4：视频；5：轮播；6.背景',
  `name` varchar(100) DEFAULT NULL COMMENT '广告位名称',
  `memo` varchar(255) DEFAULT NULL COMMENT '广告位描述',
  `width` smallint(5) unsigned DEFAULT '0' COMMENT '广告位宽度。单位：像素',
  `height` smallint(5) unsigned DEFAULT '0' COMMENT '广告位高度。单位：像素',
  `sort_order` smallint(3) NOT NULL DEFAULT '255' COMMENT '序号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态。1：启用；0：不启用；',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  `mtime` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`board_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='广告位表';
