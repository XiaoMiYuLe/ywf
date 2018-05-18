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

Date: 2015-10-22 16:05:01
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for comment_content
-- ----------------------------
DROP TABLE IF EXISTS `comment_content`;
CREATE TABLE `comment_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级评论 ID',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '评论分类 ID',
  `to_id` int(11) DEFAULT '0' COMMENT '评论所针对的 ID。比如商品 ID 或者优惠券 ID 等',
  `to_order_item_id` int(11) DEFAULT '0' COMMENT '评论针对的订单商品编号',
  `title` varchar(200) DEFAULT '' COMMENT '评论标题',
  `content` text COMMENT '评论内容',
  `rank_base` tinyint(3) DEFAULT '0' COMMENT '商品满意度评分。分值：0 - 100',
  `rank_logistics` tinyint(3) DEFAULT '0' COMMENT '物流满意度评分。分值：0 - 100',
  `rank_speed` tinyint(3) DEFAULT '0' COMMENT '发货速度满意度评分。分值：0 - 100',
  `user_type` enum('cas','store') DEFAULT 'cas' COMMENT '评论用户的类型。cas：普通用户；store：商户；',
  `userid` int(10) NOT NULL DEFAULT '0' COMMENT ' 若发布者为登录会员，则记录会员 ID',
  `nums_reply` int(10) NOT NULL DEFAULT '0' COMMENT '回复数量',
  `nums_agree` int(10) NOT NULL DEFAULT '0' COMMENT '支持数量。也叫赞、顶等',
  `lastreply_userid` int(10) NOT NULL DEFAULT '0' COMMENT '最后一次回复的用户 ID',
  `ip` char(15) NOT NULL,
  `is_agen` tinyint(1) DEFAULT '0' COMMENT '是否是追加评论。1：是；0：否；',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否在前台显示。1：是；0：否；',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否标记为删除。1：是；0：否；',
  `ctime` datetime NOT NULL COMMENT '记录创建时间',
  `mtime` datetime NOT NULL COMMENT '最后回复时间。仅记录在第一级评论记录上',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `to_id` (`to_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='评论主体表';
