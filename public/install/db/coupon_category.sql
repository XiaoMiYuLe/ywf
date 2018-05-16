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

Date: 2015-11-05 11:02:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for coupon_category
-- ----------------------------
DROP TABLE IF EXISTS `coupon_category`;
CREATE TABLE `coupon_category` (
  `coupon_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '优惠券ＩＤ',
  `coupon_name` varchar(255) DEFAULT NULL COMMENT '优惠券名称',
  `total` mediumint(8) NOT NULL DEFAULT '0' COMMENT '优惠券发行数量',
  `exchanged_total` mediumint(8) DEFAULT '0' COMMENT '实际生成数量',
  `face_value` decimal(6,2) DEFAULT '0.00' COMMENT '优惠券面额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '优惠券状态。未启用：0 ，已启用：1，异常：-1',
  `coupon_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '优惠券类型。1：全场；2：满减；',
  `coupon_point` mediumint(8) unsigned DEFAULT NULL COMMENT '兑换所需积分',
  `disabled` tinyint(1) DEFAULT '0' COMMENT '失效,1:失效 0:未失效',
  `is_exchange` tinyint(4) DEFAULT '0' COMMENT '是否允许积分兑换 : 0 不允许 1 允许',
  `rule` text COMMENT '使用规则',
  `body` text COMMENT '使用详情',
  `valid_stime` datetime DEFAULT NULL COMMENT '有效开始时间',
  `valid_etime` datetime DEFAULT NULL COMMENT '有效结束时间',
  `grant_stime` datetime DEFAULT NULL COMMENT '发放开始时间',
  `grant_etime` datetime DEFAULT NULL COMMENT '发放结束时间',
  `is_del` tinyint(255) DEFAULT NULL COMMENT '是否标记删除  0 未删除 1 已删除',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  `mtime` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`coupon_id`),
  KEY `ind_disabled` (`disabled`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='优惠券主表';
