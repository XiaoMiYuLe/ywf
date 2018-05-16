/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : bm_yumzeed_huds

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2015-11-10 17:13:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bts_order_attachment
-- ----------------------------
DROP TABLE IF EXISTS `bts_order_attachment`;
CREATE TABLE `bts_order_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_id` varchar(32) DEFAULT '0' COMMENT '所针对表的主键 ID，或唯一标识字段，比如退货单号',
  `attachmentid` int(11) NOT NULL DEFAULT '0' COMMENT '附件ID',
  `userid` int(11) NOT NULL DEFAULT '0' COMMENT '上传的用户 ID',
  `type` enum('order','order_return') NOT NULL DEFAULT 'order' COMMENT '附件类型。order：订单；order_return：退货单；',
  PRIMARY KEY (`id`,`attachmentid`,`userid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单附件关系表';
