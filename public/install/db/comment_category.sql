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

Date: 2015-10-22 16:04:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for comment_category
-- ----------------------------
DROP TABLE IF EXISTS `comment_category`;
CREATE TABLE `comment_category` (
  `category_id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL DEFAULT '',
  `hid` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of comment_category
-- ----------------------------
INSERT INTO `comment_category` VALUES ('2', '0', '商品评论', '0:0002');
