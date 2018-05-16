/*!40101 SET NAMES utf8 */; 
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('advert', 'secret', '广告模块', 1, 1, 0, 1, 0, 1, '', 6);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '广告管理', NULL, '1', NULL, 'fa-user', 'bg-user', 1, '1', NOW(), NOW());
SET @advert_id = LAST_INSERT_ID();
SET @advert_lev = (SELECT CASE WHEN @advert_id < 10 AND @advert_id > 0 THEN CONCAT('000', @advert_id)
WHEN @advert_id < 100 AND @advert_id >= 10 THEN CONCAT('00', @advert_id)
ELSE CONCAT('0', @advert_id) END); 
SET @advert_fhid = CONCAT('0:0001:', @advert_lev);
UPDATE system_navigation SET hid = @advert_fhid WHERE navigation_id=@advert_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @advert_id, null, '广告管理', '/advertadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @advert_sid = LAST_INSERT_ID();
SET @advert_lev = (SELECT CASE WHEN @advert_sid < 10 AND @advert_sid > 0 THEN CONCAT('000', @advert_sid)
WHEN @advert_sid < 100 AND @advert_sid >= 10 THEN CONCAT('00', @advert_sid)
ELSE CONCAT('0', @advert_sid) END);
SET @advert_hid = CONCAT(@advert_fhid, ':', @advert_lev);
UPDATE system_navigation SET hid = @advert_hid WHERE navigation_id=@advert_sid;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @advert_id, null, '广告位管理', '/advertadmin/board', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @advert_sid = LAST_INSERT_ID();
SET @advert_lev = (SELECT CASE WHEN @advert_sid < 10 AND @advert_sid > 0 THEN CONCAT('000', @advert_sid)
WHEN @advert_sid < 100 AND @advert_sid >= 10 THEN CONCAT('00', @advert_sid)
ELSE CONCAT('0', @advert_sid) END);
SET @advert_hid = CONCAT(@advert_fhid, ':', @advert_lev);
UPDATE system_navigation SET hid = @advert_hid WHERE navigation_id=@advert_sid;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @advert_id, null, '广告页管理', '/advertadmin/page', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @advert_sid = LAST_INSERT_ID();
SET @advert_lev = (SELECT CASE WHEN @advert_sid < 10 AND @advert_sid > 0 THEN CONCAT('000', @advert_sid)
WHEN @advert_sid < 100 AND @advert_sid >= 10 THEN CONCAT('00', @advert_sid)
ELSE CONCAT('0', @advert_sid) END);
SET @advert_hid = CONCAT(@advert_fhid, ':', @advert_lev);
UPDATE system_navigation SET hid = @advert_hid WHERE navigation_id=@advert_sid;﻿/*!40101 SET NAMES utf8 */
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('cart', 'cart_secret', '购物车模块', 0, 1, 0, 1, 0, 1, '', 5);﻿/*!40101 SET NAMES utf8 */;
--
-- 插入模块表
--
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('feedback', 'secret', '反馈模块', 1, 1, 0, 1, 0, 1, '', 255);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '反馈管理', NULL, '1', NULL, 'fa-user', 'bg-user', 255, '1', NOW(), NOW());
SET @feedback_id = LAST_INSERT_ID();
SET @feedback_lev = (SELECT CASE WHEN @feedback_id < 10 AND @feedback_id > 0 THEN CONCAT('000', @feedback_id)
WHEN @feedback_id < 100 AND @feedback_id >= 10 THEN CONCAT('00', @feedback_id)
ELSE CONCAT('0', @feedback_id) END); 
SET @feedback_fhid = CONCAT('0:0001:', @feedback_lev);
UPDATE system_navigation SET hid = @feedback_fhid WHERE navigation_id=@feedback_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @feedback_id, NULL, '反馈列表', '/feedbackadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @feedback_sid = LAST_INSERT_ID();
SET @feedback_lev = (SELECT CASE WHEN @feedback_sid < 10 AND @feedback_sid > 0 THEN CONCAT('000', @feedback_sid)
WHEN @feedback_sid < 100 AND @feedback_sid >= 10 THEN CONCAT('00', @feedback_sid)
ELSE CONCAT('0', @feedback_sid) END);
SET @feedback_hid = CONCAT(@feedback_fhid, ':', @feedback_lev);
UPDATE system_navigation SET hid = @feedback_hid WHERE navigation_id=@feedback_sid;
/*!40101 SET NAMES utf8 */;
--
-- 插入模块表
--
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('cas', 'secret', '会员中心模块', 1, 1, 0, 1, 0, 1, '', 255);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '会员管理', NULL, '1', NULL, 'fa-user', 'bg-user', 255, '1', NOW(), NOW());
SET @cas_id = LAST_INSERT_ID();
SET @cas_lev = (SELECT CASE WHEN @cas_id < 10 AND @cas_id > 0 THEN CONCAT('000', @cas_id)
WHEN @cas_id < 100 AND @cas_id >= 10 THEN CONCAT('00', @cas_id)
ELSE CONCAT('0', @cas_id) END); 
SET @cas_fhid = CONCAT('0:0001:', @cas_lev);
UPDATE system_navigation SET hid = @cas_fhid WHERE navigation_id=@cas_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @cas_id, NULL, '会员列表', '/casadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @cas_sid = LAST_INSERT_ID();
SET @cas_lev = (SELECT CASE WHEN @cas_sid < 10 AND @cas_sid > 0 THEN CONCAT('000', @cas_sid)
WHEN @cas_sid < 100 AND @cas_sid >= 10 THEN CONCAT('00', @cas_sid)
ELSE CONCAT('0', @cas_sid) END);
SET @cas_hid = CONCAT(@cas_fhid, ':', @cas_lev);
UPDATE system_navigation SET hid = @cas_hid WHERE navigation_id=@cas_sid;
﻿/*!40101 SET NAMES utf8 */;
--
-- 插入模块表
--
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('promotion', 'secret', '活动模块', 1, 1, 0, 1, 0, 1, '', 255);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '活动管理', NULL, '1', NULL, 'fa-user', 'bg-user', 255, '1', NOW(), NOW());
SET @promotion_id = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_id < 10 AND @promotion_id > 0 THEN CONCAT('000', @promotion_id)
WHEN @promotion_id < 100 AND @promotion_id >= 10 THEN CONCAT('00', @promotion_id)
ELSE CONCAT('0', @promotion_id) END); 
SET @promotion_fhid = CONCAT('0:0001:', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_fhid WHERE navigation_id=@promotion_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL,  @promotion_id,NULL, '活动分类管理', '/promotionadmin/category', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @promotion_sid = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_sid < 10 AND @promotion_sid > 0 THEN CONCAT('000', @promotion_sid)
WHEN @promotion_sid < 100 AND @promotion_sid >= 10 THEN CONCAT('00', @promotion_sid)
ELSE CONCAT('0', @promotion_sid) END);
SET @promotion_hid = CONCAT(@promotion_fhid, ':', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_hid WHERE navigation_id=@promotion_sid;


INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL,  @promotion_id,NULL, '活动管理', '/promotionadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @promotion_sid = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_sid < 10 AND @promotion_sid > 0 THEN CONCAT('000', @promotion_sid)
WHEN @promotion_sid < 100 AND @promotion_sid >= 10 THEN CONCAT('00', @promotion_sid)
ELSE CONCAT('0', @promotion_sid) END);
SET @promotion_hid = CONCAT(@promotion_fhid, ':', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_hid WHERE navigation_id=@promotion_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL,  @promotion_id,NULL, '活动模板管理', '/promotionadmin/template', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @promotion_sid = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_sid < 10 AND @promotion_sid > 0 THEN CONCAT('000', @promotion_sid)
WHEN @promotion_sid < 100 AND @promotion_sid >= 10 THEN CONCAT('00', @promotion_sid)
ELSE CONCAT('0', @promotion_sid) END);
SET @promotion_hid = CONCAT(@promotion_fhid, ':', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_hid WHERE navigation_id=@promotion_sid;﻿/*!40101 SET NAMES utf8 */;
--
-- 插入模块表
--
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('promotion', 'secret', '活动模块', 1, 1, 0, 1, 0, 1, '', 255);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '活动管理', NULL, '1', NULL, 'fa-user', 'bg-user', 255, '1', NOW(), NOW());
SET @promotion_id = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_id < 10 AND @promotion_id > 0 THEN CONCAT('000', @promotion_id)
WHEN @promotion_id < 100 AND @promotion_id >= 10 THEN CONCAT('00', @promotion_id)
ELSE CONCAT('0', @promotion_id) END); 
SET @promotion_fhid = CONCAT('0:0001:', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_fhid WHERE navigation_id=@promotion_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL,  @promotion_id,NULL, '活动分类管理', '/promotionadmin/category', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @promotion_sid = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_sid < 10 AND @promotion_sid > 0 THEN CONCAT('000', @promotion_sid)
WHEN @promotion_sid < 100 AND @promotion_sid >= 10 THEN CONCAT('00', @promotion_sid)
ELSE CONCAT('0', @promotion_sid) END);
SET @promotion_hid = CONCAT(@promotion_fhid, ':', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_hid WHERE navigation_id=@promotion_sid;


INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL,  @promotion_id,NULL, '活动管理', '/promotionadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @promotion_sid = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_sid < 10 AND @promotion_sid > 0 THEN CONCAT('000', @promotion_sid)
WHEN @promotion_sid < 100 AND @promotion_sid >= 10 THEN CONCAT('00', @promotion_sid)
ELSE CONCAT('0', @promotion_sid) END);
SET @promotion_hid = CONCAT(@promotion_fhid, ':', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_hid WHERE navigation_id=@promotion_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL,  @promotion_id,NULL, '活动模板管理', '/promotionadmin/template', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @promotion_sid = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_sid < 10 AND @promotion_sid > 0 THEN CONCAT('000', @promotion_sid)
WHEN @promotion_sid < 100 AND @promotion_sid >= 10 THEN CONCAT('00', @promotion_sid)
ELSE CONCAT('0', @promotion_sid) END);
SET @promotion_hid = CONCAT(@promotion_fhid, ':', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_hid WHERE navigation_id=@promotion_sid;/*!40101 SET NAMES utf8 */; 
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('groupon', 'secret', '团购抢购', 1, 1, 0, 1, 0, 1, '', 6);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '抢购管理', NULL, '1', NULL, 'fa-user', 'bg-user', 1, '1', NOW(), NOW());
SET @groupon_id = LAST_INSERT_ID();
SET @groupon_lev = (SELECT CASE WHEN @groupon_id < 10 AND @groupon_id > 0 THEN CONCAT('000', @groupon_id)
WHEN @groupon_id < 100 AND @groupon_id >= 10 THEN CONCAT('00', @groupon_id)
ELSE CONCAT('0', @groupon_id) END); 
SET @groupon_fhid = CONCAT('0:0001:', @groupon_lev);
UPDATE system_navigation SET hid = @groupon_fhid WHERE navigation_id=@groupon_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @groupon_id, null, '标签管理', '/grouponadmin/category', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @groupon_sid = LAST_INSERT_ID();
SET @groupon_lev = (SELECT CASE WHEN @groupon_sid < 10 AND @groupon_sid > 0 THEN CONCAT('000', @groupon_sid)
WHEN @groupon_sid < 100 AND @groupon_sid >= 10 THEN CONCAT('00', @groupon_sid)
ELSE CONCAT('0', @groupon_sid) END);
SET @groupon_hid = CONCAT(@groupon_fhid, ':', @groupon_lev);
UPDATE system_navigation SET hid = @groupon_hid WHERE navigation_id=@groupon_sid;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @groupon_id, null, '团购管理', '/grouponadmin/index', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @groupon_sid = LAST_INSERT_ID();
SET @groupon_lev = (SELECT CASE WHEN @groupon_sid < 10 AND @groupon_sid > 0 THEN CONCAT('000', @groupon_sid)
WHEN @groupon_sid < 100 AND @groupon_sid >= 10 THEN CONCAT('00', @groupon_sid)
ELSE CONCAT('0', @groupon_sid) END);
SET @groupon_hid = CONCAT(@groupon_fhid, ':', @groupon_lev);
UPDATE system_navigation SET hid = @groupon_hid WHERE navigation_id=@groupon_sid;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @groupon_id, null, '抢购管理', '/grouponadmin/purch', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @groupon_sid = LAST_INSERT_ID();
SET @groupon_lev = (SELECT CASE WHEN @groupon_sid < 10 AND @groupon_sid > 0 THEN CONCAT('000', @groupon_sid)
WHEN @groupon_sid < 100 AND @groupon_sid >= 10 THEN CONCAT('00', @groupon_sid)
ELSE CONCAT('0', @groupon_sid) END);
SET @groupon_hid = CONCAT(@groupon_fhid, ':', @groupon_lev);
UPDATE system_navigation SET hid = @groupon_hid WHERE navigation_id=@groupon_sid;/*!40101 SET NAMES utf8 */; 
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('comment', 'secret', '评论模块', 1, 1, 0, 1, 0, 1, '', 6);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '评论管理', NULL, '1', NULL, 'fa-user', 'bg-user', 1, '1', NOW(), NOW());
SET @comment_id = LAST_INSERT_ID();
SET @comment_lev = (SELECT CASE WHEN @comment_id < 10 AND @comment_id > 0 THEN CONCAT('000', @comment_id)
WHEN @comment_id < 100 AND @comment_id >= 10 THEN CONCAT('00', @comment_id)
ELSE CONCAT('0', @comment_id) END); 
SET @comment_fhid = CONCAT('0:0001:', @comment_lev);
UPDATE system_navigation SET hid = @comment_fhid WHERE navigation_id=@comment_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @comment_id, null, '评价列表管理', '/commentadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @comment_sid = LAST_INSERT_ID();
SET @comment_lev = (SELECT CASE WHEN @comment_sid < 10 AND @comment_sid > 0 THEN CONCAT('000', @comment_sid)
WHEN @comment_sid < 100 AND @comment_sid >= 10 THEN CONCAT('00', @comment_sid)
ELSE CONCAT('0', @comment_sid) END);
SET @comment_hid = CONCAT(@comment_fhid, ':', @comment_lev);
UPDATE system_navigation SET hid = @comment_hid WHERE navigation_id=@comment_sid;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @comment_id, null, '评价回收站', '/commentadmin/trash', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @comment_sid = LAST_INSERT_ID();
SET @comment_lev = (SELECT CASE WHEN @comment_sid < 10 AND @comment_sid > 0 THEN CONCAT('000', @comment_sid)
WHEN @comment_sid < 100 AND @comment_sid >= 10 THEN CONCAT('00', @comment_sid)
ELSE CONCAT('0', @comment_sid) END);
SET @comment_hid = CONCAT(@comment_fhid, ':', @comment_lev);
UPDATE system_navigation SET hid = @comment_hid WHERE navigation_id=@comment_sid;/*!40101 SET NAMES utf8 */;
--
-- 插入模块表
--
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('push', 'push_secret', '推送组件', 1, 1, 0, 1, 0, 1, '', 255);
﻿/*!40101 SET NAMES utf8 */;
--
-- 插入模块表
--
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('goods', 'secret', '商品模块', 1, 1, 0, 1, 0, 1, '', 255);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '商品管理', NULL, '1', NULL, 'fa-shopping-cart', 'bg-primary', 255, '1', NOW(), NOW());
SET @goods_id = LAST_INSERT_ID();
SET @goods_lev = (SELECT CASE WHEN @goods_id < 10 AND @goods_id > 0 THEN CONCAT('000', @goods_id)
WHEN @goods_id < 100 AND @goods_id >= 10 THEN CONCAT('00', @goods_id)
ELSE CONCAT('0', @goods_id) END); 
SET @goods_fhid = CONCAT('0:0001:', @goods_lev);
UPDATE system_navigation SET hid = @goods_fhid WHERE navigation_id=@goods_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @goods_id, NULL, '商品列表管理', '/goodsadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @goods_sid = LAST_INSERT_ID();
SET @goods_lev = (SELECT CASE WHEN @goods_sid < 10 AND @goods_sid > 0 THEN CONCAT('000', @goods_sid)
WHEN @goods_sid < 100 AND @goods_sid >= 10 THEN CONCAT('00', @goods_sid)
ELSE CONCAT('0', @goods_sid) END);
SET @goods_hid = CONCAT(@goods_fhid, ':', @goods_lev);
UPDATE system_navigation SET hid = @goods_hid WHERE navigation_id=@goods_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @goods_id, NULL, '添加新商品', '/goodsadmin/index/add', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @goods_sid = LAST_INSERT_ID();
SET @goods_lev = (SELECT CASE WHEN @goods_sid < 10 AND @goods_sid > 0 THEN CONCAT('000', @goods_sid)
WHEN @goods_sid < 100 AND @goods_sid >= 10 THEN CONCAT('00', @goods_sid)
ELSE CONCAT('0', @goods_sid) END);
SET @goods_hid = CONCAT(@goods_fhid, ':', @goods_lev);
UPDATE system_navigation SET hid = @goods_hid WHERE navigation_id=@goods_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @goods_id, NULL, '商品分类管理', '/goodsadmin/category', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @goods_sid = LAST_INSERT_ID();
SET @goods_lev = (SELECT CASE WHEN @goods_sid < 10 AND @goods_sid > 0 THEN CONCAT('000', @goods_sid)
WHEN @goods_sid < 100 AND @goods_sid >= 10 THEN CONCAT('00', @goods_sid)
ELSE CONCAT('0', @goods_sid) END);
SET @goods_hid = CONCAT(@goods_fhid, ':', @goods_lev);
UPDATE system_navigation SET hid = @goods_hid WHERE navigation_id=@goods_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @goods_id, NULL, '商品品牌管理', '/goodsadmin/brand', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @goods_sid = LAST_INSERT_ID();
SET @goods_lev = (SELECT CASE WHEN @goods_sid < 10 AND @goods_sid > 0 THEN CONCAT('000', @goods_sid)
WHEN @goods_sid < 100 AND @goods_sid >= 10 THEN CONCAT('00', @goods_sid)
ELSE CONCAT('0', @goods_sid) END);
SET @goods_hid = CONCAT(@goods_fhid, ':', @goods_lev);
UPDATE system_navigation SET hid = @goods_hid WHERE navigation_id=@goods_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @goods_id, NULL, '商品属性管理', '/trendadmin/property', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @goods_sid = LAST_INSERT_ID();
SET @goods_lev = (SELECT CASE WHEN @goods_sid < 10 AND @goods_sid > 0 THEN CONCAT('000', @goods_sid)
WHEN @goods_sid < 100 AND @goods_sid >= 10 THEN CONCAT('00', @goods_sid)
ELSE CONCAT('0', @goods_sid) END);
SET @goods_hid = CONCAT(@goods_fhid, ':', @goods_lev);
UPDATE system_navigation SET hid = @goods_hid WHERE navigation_id=@goods_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @goods_id, NULL, '属性分组管理', '/trendadmin/propertyGroup', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @goods_sid = LAST_INSERT_ID();
SET @goods_lev = (SELECT CASE WHEN @goods_sid < 10 AND @goods_sid > 0 THEN CONCAT('000', @goods_sid)
WHEN @goods_sid < 100 AND @goods_sid >= 10 THEN CONCAT('00', @goods_sid)
ELSE CONCAT('0', @goods_sid) END);
SET @goods_hid = CONCAT(@goods_fhid, ':', @goods_lev);
UPDATE system_navigation SET hid = @goods_hid WHERE navigation_id=@goods_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @goods_id, NULL, '商品回收站', '/goodsadmin/trash', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @goods_sid = LAST_INSERT_ID();
SET @goods_lev = (SELECT CASE WHEN @goods_sid < 10 AND @goods_sid > 0 THEN CONCAT('000', @goods_sid)
WHEN @goods_sid < 100 AND @goods_sid >= 10 THEN CONCAT('00', @goods_sid)
ELSE CONCAT('0', @goods_sid) END);
SET @goods_hid = CONCAT(@goods_fhid, ':', @goods_lev);
UPDATE system_navigation SET hid = @goods_hid WHERE navigation_id=@goods_sid;
/*!40101 SET NAMES utf8 */;
--
-- 插入模块表
--
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('store', 'store_secret', '商户模块', 1, 1, 0, 1, 0, 1, '', 255);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '商户管理', NULL, '1', NULL, 'fa-user', 'bg-user', 255, '1', NOW(), NOW());
SET @store_id = LAST_INSERT_ID();
SET @store_lev = (SELECT CASE WHEN @store_id < 10 AND @store_id > 0 THEN CONCAT('000', @store_id)
WHEN @store_id < 100 AND @store_id >= 10 THEN CONCAT('00', @store_id)
ELSE CONCAT('0', @store_id) END); 
SET @store_fhid = CONCAT('0:0001:', @store_lev);
UPDATE system_navigation SET hid = @store_fhid WHERE navigation_id=@store_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @store_id, NULL, '商户列表管理', '/storeadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @store_sid = LAST_INSERT_ID();
SET @store_lev = (SELECT CASE WHEN @store_sid < 10 AND @store_sid > 0 THEN CONCAT('000', @store_sid)
WHEN @store_sid < 100 AND @store_sid >= 10 THEN CONCAT('00', @store_sid)
ELSE CONCAT('0', @store_sid) END);
SET @store_hid = CONCAT(@store_fhid, ':', @store_lev);
UPDATE system_navigation SET hid = @store_hid WHERE navigation_id=@store_sid;

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @store_id, NULL, '添加商户', '/storeadmin/index/add', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @store_sid = LAST_INSERT_ID();
SET @store_lev = (SELECT CASE WHEN @store_sid < 10 AND @store_sid > 0 THEN CONCAT('000', @store_sid)
WHEN @store_sid < 100 AND @store_sid >= 10 THEN CONCAT('00', @store_sid)
ELSE CONCAT('0', @store_sid) END);
SET @store_hid = CONCAT(@store_fhid, ':', @store_lev);
UPDATE system_navigation SET hid = @store_hid WHERE navigation_id=@store_sid;
﻿/*!40101 SET NAMES utf8 */;

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '优惠券管理', NULL, '1', NULL, 'fa-user', 'bg-user', 255, '1', NOW(), NOW());
SET @promotion_id = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_id < 10 AND @promotion_id > 0 THEN CONCAT('000', @promotion_id)
WHEN @promotion_id < 100 AND @promotion_id >= 10 THEN CONCAT('00', @promotion_id)
ELSE CONCAT('0', @promotion_id) END); 
SET @promotion_fhid = CONCAT('0:0001:', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_fhid WHERE navigation_id=@promotion_id;

--
-- 二级菜单
--

INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL,  @promotion_id,NULL, '优惠券管理', '/couponadmin', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @promotion_sid = LAST_INSERT_ID();
SET @promotion_lev = (SELECT CASE WHEN @promotion_sid < 10 AND @promotion_sid > 0 THEN CONCAT('000', @promotion_sid)
WHEN @promotion_sid < 100 AND @promotion_sid >= 10 THEN CONCAT('00', @promotion_sid)
ELSE CONCAT('0', @promotion_sid) END);
SET @promotion_hid = CONCAT(@promotion_fhid, ':', @promotion_lev);
UPDATE system_navigation SET hid = @promotion_hid WHERE navigation_id=@promotion_sid;
/*!40101 SET NAMES utf8 */; 
INSERT INTO `admin_app` (`appkey`, `appsecret`, `name`, `if_compose`, `ifadmin`, `ifchannel`, `ifpubplus`, `moveable`, `installed`, `classtbl`, `sort_order`) VALUES
('bts', 'secret', '订单管理', 1, 1, 0, 1, 0, 1, '', 6);

--
-- 一级菜单
-- 注:变量带自己模块前缀用以区分其他模块变量
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, '1', NULL, '订单管理', NULL, '1', NULL, 'fa-user', 'bg-user', 1, '1', NOW(), NOW());
SET @bts_id = LAST_INSERT_ID();
SET @bts_lev = (SELECT CASE WHEN @bts_id < 10 AND @bts_id > 0 THEN CONCAT('000', @bts_id)
WHEN @bts_id < 100 AND @bts_id >= 10 THEN CONCAT('00', @bts_id)
ELSE CONCAT('0', @bts_id) END); 
SET @bts_fhid = CONCAT('0:0001:', @bts_lev);
UPDATE system_navigation SET hid = @bts_fhid WHERE navigation_id=@bts_id;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @bts_id, null, '订单列表管理', '/btsadmin/order', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @bts_sid = LAST_INSERT_ID();
SET @bts_lev = (SELECT CASE WHEN @bts_sid < 10 AND @bts_sid > 0 THEN CONCAT('000', @bts_sid)
WHEN @bts_sid < 100 AND @bts_sid >= 10 THEN CONCAT('00', @bts_sid)
ELSE CONCAT('0', @bts_sid) END);
SET @bts_hid = CONCAT(@bts_fhid, ':', @bts_lev);
UPDATE system_navigation SET hid = @bts_hid WHERE navigation_id=@bts_sid;

--
-- 二级菜单（多个二级复制该逻辑）
--
INSERT INTO `system_navigation` (`navigation_id`, `parent_id`, `hid`, `title`, `link`, `load_type`, `description`, `icon`, `icon_bg`, `sort_order`, `status`, `ctime`, `mtime`) VALUES 
(NULL, @bts_id, null, '退货单管理', '/btsadmin/orderRefund', '1', NULL, 'fa-file-text', '', 255, '1', NOW(), NOW());
SET @bts_sid = LAST_INSERT_ID();
SET @bts_lev = (SELECT CASE WHEN @bts_sid < 10 AND @bts_sid > 0 THEN CONCAT('000', @bts_sid)
WHEN @bts_sid < 100 AND @bts_sid >= 10 THEN CONCAT('00', @bts_sid)
ELSE CONCAT('0', @bts_sid) END);
SET @bts_hid = CONCAT(@bts_fhid, ':', @bts_lev);
UPDATE system_navigation SET hid = @bts_hid WHERE navigation_id=@bts_sid;