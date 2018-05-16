/*!40101 SET NAMES utf8 */;

/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : bm_yumzeed_zr

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2015-11-05 11:02:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for coupon_relation
-- ----------------------------
DROP TABLE IF EXISTS `coupon_relation`;
CREATE TABLE `coupon_relation` (
  `coupon_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '优惠券id',
  `basic_price` decimal(6,2) DEFAULT '0.00' COMMENT '活动金额基础要求',
  `relation_type` tinyint(4) DEFAULT NULL COMMENT '类型。1：全场满减；2： 商品分类；3：商户ID；',
  `relation_content` varchar(50) NOT NULL DEFAULT '' COMMENT '关联内容ID',
  PRIMARY KEY (`coupon_id`),
  KEY `ind_cpns_prefix` (`relation_content`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
