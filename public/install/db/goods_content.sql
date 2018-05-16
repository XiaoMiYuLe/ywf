/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bm_btsm

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-23 11:48:40
*/

SET FOREIGN_KEY_CHECKS=0;
/*!40101 SET NAMES utf8 */; 
-- ----------------------------
-- Table structure for `goods_content`
-- ----------------------------
DROP TABLE IF EXISTS `goods_content`;
CREATE TABLE `goods_content` (
  `content_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '主商品id',
  `category` varchar(128) NOT NULL COMMENT '所属平台的商品分类 ID',
  `brand_id` mediumint(8) unsigned DEFAULT NULL COMMENT '品牌 ID',
  `userid` int(11) DEFAULT '0' COMMENT '商品发布者的用户 ID。对应表：admin_user',
  `name` varchar(200) NOT NULL COMMENT '商品名称',
  `sku` varchar(30) NOT NULL COMMENT '商品货号',
  `image_default` varchar(255) DEFAULT NULL COMMENT '默认图片，即封面',
  `property_related` varchar(200) NOT NULL COMMENT '属性关联组合，记作：property_id:property_value_id,property_id:property_value_id。比如颜色,尺码',
  `stock` mediumint(8) DEFAULT '0' COMMENT '库存',
  `weight` varchar(50) DEFAULT NULL COMMENT '重量。单位：克',
  `length` varchar(20) DEFAULT NULL COMMENT '长。单位：毫米（mm）',
  `wide` varchar(20) DEFAULT NULL COMMENT '宽。单位：毫米（mm）',
  `height` varchar(20) DEFAULT NULL COMMENT '高。单位：毫米（mm）',
  `price` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '销售价格',
  `price_market` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '市场价格，即参考价格',
  `price_cost` decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '成本价格。只在后台查看，前台不显示',
  `purchase_max` tinyint(3) DEFAULT '0' COMMENT '允许同一个用户购买同一件商品的最大数量，0 为不限制。',
  `sales_volume` int(11) DEFAULT '0' COMMENT '单件商品销量',
  `sales_amount` decimal(20,4) DEFAULT '0.0000' COMMENT '单件商品销售额',
  `rank_average` decimal(8,2) DEFAULT '0.00' COMMENT '单件商品评分（平均值）',
  `serialize_specs` text NOT NULL COMMENT '商品规格信息排列组合的序列化存储',
  `viewed` mediumint(8) DEFAULT '0' COMMENT '商品浏览数',
  `sort_order` mediumint(8) DEFAULT '0' COMMENT '序号。值越大，排位越靠前（由商户控制，且仅在商户店铺主页查询时有效）。',
  `rev` bigint(20) DEFAULT '1' COMMENT '当前版本号',
  `pinned` int(11) DEFAULT '0' COMMENT '权重。值越大，排位越靠前（由平台控制，且仅在全局查询时有用）。',
  `promotion_type` tinyint(1) DEFAULT '0' COMMENT '参加活动类型（若没有则为0）。1：活动；2：团购；3：抢购；',
  `is_shelf` tinyint(1) DEFAULT '1' COMMENT '是否上架销售。1：是；0：否；',
  `is_spec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启规格，0不开启，1开启',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否标记为删除。1：是；0：否；',
  `ctime` datetime DEFAULT NULL COMMENT '创建时间',
  `mtime` datetime DEFAULT NULL COMMENT '最后一次更新时间',
  PRIMARY KEY (`content_id`),
  KEY `ind_frontend` (`is_del`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='商品主表';

-- ----------------------------
-- Records of goods_content
-- ----------------------------
