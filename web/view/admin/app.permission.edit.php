<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-3-21
 * @version    SVN: $Id$
 */
require_once dirname(__FILE__) . '/view.init.php';

$data = $this->getData('data');

$appkey_from_permission = $data['appkey_from_permission'];
$permission_id_from_permission = $data['permission_id_from_permission'];

$selected_appkey = $selected_permission_id = '';
if ($appkey_from_permission) {
    $selected_appkey = $appkey_from_permission;
    $selected_permission_id = $permission_id_from_permission;
} else if ($data['app_permission']) {
    $selected_appkey = $data['app_permission']['appkey'];
    $selected_permission_id = $data['app_permission']['permission_id'];
}

$smarty->assign("app_permission", $data['app_permission']);
$smarty->assign("apps", $data['apps']);
$smarty->assign("permissions", $data['permissions']);
$smarty->assign("selected_appkey", $selected_appkey);
$smarty->assign("selected_permission_id", $selected_permission_id);

$smarty->display('app.permission.edit.html');
