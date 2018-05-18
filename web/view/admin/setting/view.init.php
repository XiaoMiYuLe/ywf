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
$smarty->addTemplateDir(ZEED_PATH_VIEW . $_module . '/setting/' . $_theme);
$smarty->addTemplateDir(ZEED_PATH_VIEW . 'admin/' . $_theme); // Default template
$smarty->addTemplateDir(ZEED_PATH_VIEW . 'panel/' . $_theme); // Panel template folder
                                                            
// 注册插件
$smarty->addPluginsDir(ZEED_PATH_LIB . 'smarty/plugins');

// 赋值 moduleman 数组对象
$smarty->assign('moduleman', array('module' => $_module, 'controller' => $_controller, 'action' => $_action, 'panel' => 'panel'));

// 登陆用户信息
$smarty->assign('loggedInUser', Com_Admin_Authorization::getLoggedInUser());

// 获取管理员权限信息，主要用于导航的访问管理
$smarty->assign('allow_navs', PermissionHelper::getAllowNavigations());

/**
 * AJAX方式不显示头尾，不加载JS、CSS
 * IFrame方式不显示头尾，加载JS、CSS
 */
$loadtype = $this->input->get('loadtype');
if (! $loadtype) {
    if ($this->input->isAjax()) {
        $loadtype = 'ajax';
    }
} else {
    $loadtype = $loadtype == 'ajax' ? 'ajax' : 'iframe';
}
if ($loadtype == 'iframe') {
    $smarty->assign('wrapper_prefix', $smarty->fetch('panel/' . $_theme . '/wrapper.prefix-tiny.html'));
    $smarty->assign('wrapper_suffix', $smarty->fetch('panel/' . $_theme . '/wrapper.suffix-tiny.html'));
    $smarty->assign('loadtype', 'iframe');
} else {
    if ($loadtype != 'ajax') {
        $smarty->assign('navigations', System_Model_Navigation::instance()->getAllForNavigation());
        $smarty->assign('wrapper_prefix', $smarty->fetch('panel/' . $_theme . '/wrapper.prefix.html'));
        $smarty->assign('wrapper_suffix', $smarty->fetch('panel/' . $_theme . '/wrapper.suffix.html'));
        $smarty->assign('loadtype', 'ajax');
    }
}

// End ^ LF ^ encoding
