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

Date: 2015-10-14 17:37:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for advert_content
-- ----------------------------
DROP TABLE IF EXISTS `advert_content`;
CREATE TABLE `advert_content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `board_id` int(11) NOT NULL COMMENT '广告位 ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '广告类型。1：图片；2：文字；3：flash；4：视频；5：轮播；',
  `page_id` int(11) DEFAULT NULL COMMENT '所属页面',
  `title` varchar(255) NOT NULL COMMENT '广告标题',
  `content` text NOT NULL COMMENT '广告详情',
  `attachmentid` bigint(20) DEFAULT NULL COMMENT '附件 ID。关联表：trend_attachment',
  `bind_type` tinyint(1) DEFAULT '1' COMMENT '绑定资源类型。1：链接地址；2：商品；3：文章；4：自定义；',
  `bind_source` varchar(255) DEFAULT NULL COMMENT '绑定资源内容',
  `count` int(11) DEFAULT '0' COMMENT '点击次数',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号',
  `status` tinyint(1) DEFAULT '1' COMMENT '显示状态。0：不显示；1：显示；2：定时显示；',
  `start_time` datetime NOT NULL COMMENT '开始生效时间',
  `end_time` datetime NOT NULL COMMENT '结束时间',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  `mtime` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`content_id`),
  UNIQUE KEY `advert_content_id` (`content_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告表';
