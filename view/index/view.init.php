<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category iNewS
 * @package ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since Apr 23, 2010
 * @version SVN: $Id$
 */

error_reporting(E_ALL & ~ E_NOTICE);

// 设置模块主题, 可改进为从个性化配置中加载.
$_module = strtolower(basename(ZEED_PATH_MODULE));
$_controller = strtolower(basename(ZEED_PATH_CONTROLLER));
$_action = strtolower(basename(ZEED_PATH_ACTION));
$_theme = 'template';

$smarty = Zeed_Smarty::instance()->setModule($_module)->setTheme($_theme);
$smarty->addTemplateDir(ZEED_PATH_VIEW . $_module . '/' . ZEED_PATH_ADMIN_OR_FRONT . $_theme);
$smarty->addTemplateDir(ZEED_PATH_VIEW . 'admin/' . $_theme); // Default template
$smarty->addTemplateDir(ZEED_PATH_VIEW . 'panel/' . $_theme); // Panel template folder

// 注册插件
$smarty->addPluginsDir(ZEED_PATH_LIB . 'smarty/plugins');

// 赋值 moduleman 数组对象
$smarty->assign('moduleman', array('module' => $_module, 'controller' => $_controller, 'action' => $_action, 'panel' => 'panel'));

// 登陆用户信息
$loggedInUser = Cas_Authorization::getLoggedInUserInfo();
$smarty->assign('loggedInUser', $loggedInUser);

// 加载单页
$page_groups = Page_Model_Group::instance()->getAllGroups();
if ($page_groups) {
	foreach($page_groups as $key => $group) {
		$temp_page_list = Page_Model_Listing::instance()->fetchByFV("group_id", $group['group_id']);
		$page_groups[$key]["page_list"] = empty($temp_page_list) ? array() : $temp_page_list;
	}
	$smarty->assign('page_groups', $page_groups);
}

$smarty->assign('user_info',$this->getData('data.user_info'));
$smarty->assign('keyword',$this->getData('data.keyword'));

// 加载页面头尾
$smarty->assign('wrapper_prefix', $smarty->fetch('index/' . $_theme . '/wrapper.prefix.html'));
$smarty->assign('wrapper_suffix', $smarty->fetch('index/' . $_theme . '/wrapper.suffix.html'));
$smarty->assign('wrapper_prefix_front', $smarty->fetch('index/' . $_theme . '/wrapper.prefix_front.html'));
$smarty->assign('wrapper_suffix_front', $smarty->fetch('index/' . $_theme . '/wrapper.suffix_front.html'));

$smarty->assign('wrapper_prefix_center', $smarty->fetch('index/' . $_theme . '/wrapper.prefix_center.html'));
$smarty->assign('wrapper_suffix_center', $smarty->fetch('index/' . $_theme . '/wrapper.suffix_center.html'));


// End ^ LF ^ encoding
