/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-11-10 17:13:43
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bts_order
-- ----------------------------
DROP TABLE IF EXISTS `bts_order`;
CREATE TABLE `bts_order` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payment_id` smallint(3) DEFAULT NULL COMMENT '支付方式 ID',
  `payment_alias` varchar(20) DEFAULT NULL COMMENT '网关标志别名',
  `order_number` varchar(32) NOT NULL COMMENT '订单号',
  `out_order_number` varchar(40) DEFAULT NULL COMMENT '外部订单号',
  `order_type` tinyint(1) DEFAULT '0' COMMENT '订单类型。0：普通；1：团购；2：抢购',
  `userid` int(11) unsigned DEFAULT NULL COMMENT '用户ID',
  `username` varchar(64) DEFAULT NULL COMMENT '用户名',
  `remark` varchar(255) DEFAULT NULL COMMENT '订单备注',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单状态。0：待处理   1：已签收    2：待付款   3：付款成功   4：待发货   5：已发货 ',
  `pay_time` datetime DEFAULT NULL COMMENT '支付时间',
  `pay_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付状态。1：已支付；0：待支付；',
  `pay_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '支付类型。1：货到付款；0：在线支付；',
  `logistics_id` smallint(5) unsigned DEFAULT '0' COMMENT '物流 ID，对应表：bts_logistics',
  `logistics_number` varchar(128) DEFAULT NULL COMMENT '物流单号',
  `consignee_name` varchar(30) DEFAULT NULL COMMENT '收货人姓名',
  `consignee_region_id` int(11) DEFAULT NULL COMMENT '收货人所在地区 ID，填写最后一级',
  `consignee_region_name` varchar(128) DEFAULT NULL COMMENT '地区名称',
  `consignee_address` varchar(100) DEFAULT NULL COMMENT '收货人详细地址',
  `consignee_zipcode` varchar(20) DEFAULT NULL COMMENT '收货人邮编',
  `consignee_email` varchar(50) DEFAULT NULL COMMENT '收货人邮箱',
  `consignee_mobile` varchar(20) DEFAULT NULL COMMENT '收货人电话',
  `is_invoice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否有发票。1：有；0：无；',
  `invoice_tax` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '发票税额',
  `invoice_belong` varchar(100) DEFAULT NULL COMMENT '发票抬头',
  `freight` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '运费',
  `count` int(20) DEFAULT NULL COMMENT '总数量',
  `total_amount` decimal(20,3) NOT NULL DEFAULT '0.000' COMMENT '总金额',
  `coupon_amount` decimal(20,3) DEFAULT NULL COMMENT '积分抵用金额',
  `delivery_time` varchar(128) DEFAULT NULL COMMENT '送货时间',
  `is_cancel` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否取消：0否，1是',
  `is_urgent` tinyint(1) DEFAULT NULL COMMENT '是否加急 1是 0否',
  `is_refund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '退货状态。1：已成功退货；2：等待审核(已申请退货)；3：已审核/待退货;',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否标记为删除状态。1：是；0：否；',
  `is_del_user` tinyint(1) DEFAULT '0' COMMENT '用户操作，是否标记为删除状态。1：是；0：否；-1：彻底删除；',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`order_id`),
  KEY `ind_pay_status` (`pay_status`),
  KEY `ind_status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='订单表';
