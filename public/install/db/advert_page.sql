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

Date: 2015-10-14 17:37:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for advert_page
-- ----------------------------
DROP TABLE IF EXISTS `advert_page`;
CREATE TABLE `advert_page` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '页面ID，主键',
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `sort_order` mediumint(8) NOT NULL DEFAULT '0' COMMENT '序号',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC COMMENT='包含广告的所有页面表';
