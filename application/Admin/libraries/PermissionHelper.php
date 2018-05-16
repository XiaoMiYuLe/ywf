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
 * @since      2010-12-30
 * @version    SVN: $Id$
 */
class PermissionHelper
{

    /**
     * 获取拥有权限的导航菜单
     *
     * @return array null
     */
    public static function getAllowNavigations ()
    {
        /* 获取登录管理员信息 */
        $loggedInUser = Com_Admin_Authorization::getLoggedInUser();
        
        if (! $loggedInUser) {
            return false;
        }
        
        /* 获取用户组信息 */
        $user_group = UserGroupModel::instance()->fetchByFV('userid', $loggedInUser['userid']);
        
        $groupid_arr = array();
        if (! empty($user_group)) {
            foreach ($user_group as $v) {
                $groupid_arr[] = $v['groupid'];
            }
        }
        
        /* 组织查询条件 */
        $where_permission = "ptype = 'user' AND parameter = '" . $loggedInUser['username'] . "'";
        if (! empty($groupid_arr)) {
            $groupids = implode(',', $groupid_arr);
            $where_permission .= " OR (ptype = 'group' AND parameter in (" . $groupids . "))";
        }
        
        /* 获取用户及用户组拥有权限的导航菜单 */
        $allow_navs_arr = array();
        $user_permission = UserPermissionModel::instance()->fetchByWhere($where_permission);
        if (! empty($user_permission)) {
            foreach ($user_permission as $v) {
                $allow_navs_arr[] = $v['navigation_hid'];
            }
        }
        return implode(',', array_unique($allow_navs_arr));
        
    }

    /**
     * 获取拥有权限的页面内部按钮/模块
     *
     * @return array null
     */
    public static function getAllowSubPages ()
    {
        /* 获取登录管理员信息 */
        $loggedInUser = Com_Admin_Authorization::getLoggedInUser();
        
        /* 获取用户组信息 */
        $user_group = UserGroupModel::instance()->fetchByFV('userid', $loggedInUser['userid']);
        
        $groupid_arr = array();
        if (! empty($user_group)) {
            foreach ($user_group as $v) {
                $groupid_arr[] = $v['groupid'];
            }
        }
        
        /* 组织查询条件 */
        $where_permission = "ptype = 'user' AND parameter = '" . $loggedInUser['username'] . "'";
        if (! empty($groupid_arr)) {
            $groupids = implode(',', $groupid_arr);
            $where_permission .= " OR (ptype = 'group' AND parameter in (" . $groupids . "))";
        }
        
        /* 获取用户及用户组拥有权限的导航菜单 */
        $allow_navs_arr = array();
        $user_permission = UserPermissionModel::instance()->fetchByWhere($where_permission);
        if (! empty($user_permission)) {
            foreach ($user_permission as $v) {
                $allow_navs_arr[] = $v['permission_id'];
            }
        }
        
        return $allow_navs_arr;
    }
}

// End ^ Native EOL ^ UTF-8