<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      2010-2-28
 * @version    SVN: $Id$
 */
$config['__PERMISSION_CLASS__'] = 'Com_Admin_Permission';

$urlmapping = Zeed_Config::loadGroup('urlmapping');

$config['permission_db_adapter'] = 'default'; //使用Com_Admin_Permission检查权限时的数据库连接配置名
$config['appkey'] = 'admin'; //权限认证中心分配的APPKEY
$config['defaultModule'] = 'admin'; //当前应用默认模块的实际模块名
$config['login_url'] = $urlmapping['store_url_login'] . '/admin/sign/in?'; //认证中心登录地址

//用户登陆后忽略检查的ACTION，小写
$config['pm_login_ignore'] = array(
        'admin.nocontroller.noaction',
        'admin.nocontroller.noaction'
);

//忽略检查权限的ACTION，用户无须登录即可访问，谨慎使用，小写
$config['pm_ignore'] = array(
        'sign',
        'panel.api',
        'shell',
        'noadmin.nocontroller.noaction'
);

return $config;

// End ^ LF ^ UTF-8
