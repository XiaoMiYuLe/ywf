/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-11-10 17:14:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bts_order_log
-- ----------------------------
DROP TABLE IF EXISTS `bts_order_log`;
CREATE TABLE `bts_order_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '订单日志ID',
  `admin_userid` int(11) DEFAULT NULL COMMENT '管理员ID',
  `type` tinyint(4) DEFAULT NULL COMMENT '操作分类 1日志 2跟踪记录 3售后消息',
  `order_id` int(11) DEFAULT NULL COMMENT '逻辑外键 订单ID',
  `order_number` varchar(32) DEFAULT NULL COMMENT '订单号',
  `content` varchar(255) DEFAULT NULL COMMENT '操作内容',
  `remark` varchar(255) DEFAULT NULL COMMENT '描述',
  `ip` varchar(30) DEFAULT NULL COMMENT '操作人IP',
  `ctime` datetime DEFAULT NULL COMMENT '创建数据日期',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='订单操作日志表';
