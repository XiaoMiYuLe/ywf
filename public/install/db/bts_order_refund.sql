/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-11-10 17:14:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bts_order_refund
-- ----------------------------
DROP TABLE IF EXISTS `bts_order_refund`;
CREATE TABLE `bts_order_refund` (
  `refund_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL COMMENT '订单id',
  `refund_sn` varchar(32) DEFAULT NULL COMMENT '退货单号',
  `item_id` bigint(11) NOT NULL COMMENT '订单详情ID',
  `order_number` varchar(32) DEFAULT NULL COMMENT '订单号',
  `price` decimal(10,2) DEFAULT NULL COMMENT '需要补交费用 ',
  `reason` varchar(255) DEFAULT NULL COMMENT '退货原因',
  `operator_type` enum('admin','cas') DEFAULT 'cas' COMMENT '操作者类型。admin：后台管理员；cas：前台会员；',
  `operator_userid` int(11) DEFAULT '0' COMMENT '操作者用户 ID',
  `ip` varchar(30) DEFAULT NULL COMMENT '操作人IP',
  `ctime` datetime DEFAULT NULL COMMENT '创建数据日期',
  `status` tinyint(1) DEFAULT '0' COMMENT '退货状态。1：已成功退货；2：等待审核(已申请退货)；3：已审核/待退货；',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否标记为删除状态。1：是；0：否；2 彻底删除',
  PRIMARY KEY (`refund_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='退货流程日志表';
