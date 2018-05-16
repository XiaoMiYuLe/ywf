/*!40101 SET NAMES utf8 */

DROP TABLE IF EXISTS `bts_cart`;
CREATE TABLE `bts_cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '购物车ID',
  `content_id` int(11) DEFAULT NULL COMMENT '商品ID',
  `userid` int(11) DEFAULT NULL COMMENT '用户主键ID',
  `session_id` varchar(32) DEFAULT NULL COMMENT 'SESSION_ID',
  `quantity` smallint(6) unsigned DEFAULT NULL COMMENT '数量',
  `ctime` datetime DEFAULT NULL COMMENT '加入时间',
  PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='购物车';
