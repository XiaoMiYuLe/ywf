/*!40101 SET NAMES utf8 */;
/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-10-23 10:48:38
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for promotion_content
-- ----------------------------
DROP TABLE IF EXISTS `promotion_content`;
CREATE TABLE `promotion_content` (
  `promotion_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` mediumint(8) NOT NULL DEFAULT '0' COMMENT '分类 ID',
  `title` varchar(128) DEFAULT NULL COMMENT '活动标题',
  `content` text COMMENT '活动内容描述',
  `rules` text COMMENT '序列化存储的活动规则具体数据',
  `image` varchar(255) DEFAULT NULL COMMENT '活动主题图片',
  `is_overall` tinyint(1) DEFAULT '0' COMMENT '是否是全场活动。1：是；0：否；',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否启用。1：是；0：否；',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否标记为删除。1：是；0：否；',
  `start_time` date DEFAULT NULL COMMENT '活动开始时间',
  `end_time` date DEFAULT NULL COMMENT '活动结束时间',
  `is_verify` tinyint(1) DEFAULT '0' COMMENT '是否审核  0：未审核 1 审核中 2 审核通过',
  `ctime` datetime NOT NULL COMMENT '记录创建时间',
  `mtime` datetime NOT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`promotion_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='优惠促销活动主体表';
