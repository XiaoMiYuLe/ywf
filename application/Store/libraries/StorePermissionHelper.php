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

class StorePermissionHelper
{
    /**
     * 获取拥有权限的导航菜单
     * 
     * @return array|null
     */
    public static function getAllowNavigations()
    {
        /* 获取登录商户信息 */
        $loggedInUser = Cas_Authorization::getLoggedInUserInfo();
        
        if (! $loggedInUser) {
        	return false;
        }
        
        /* 获取所有导航信息 */
        $menus = System_Model_Frontend_Menu::instance()->getAllForNavigation(1);
        
        /* 如果是商户组用户，则直接返回所有导航信息 */
        if ($loggedInUser['user_group_id'] == 2) {
            return $menus;
        }
        
        /* 如果是员工组用户，则根据其分配权限返回导航信息 */
        if ($loggedInUser['user_group_id'] == 3) {
            // 获取当前登录会员已有权限信息
            $user_auth = Cas_Model_User_Auth::instance()->fetchByFV('userid', $loggedInUser['userid']);
            
            // 组织已有权限判断条件
            $user_auth_amend = array();
            if (is_array($user_auth) && count($user_auth)) {
                foreach ($user_auth as $v) {
                    $user_auth_amend[] = $v['menu_id'];
                }
            }
            
            // 过滤导航
            if (! empty($menus)) {
                $top_existent = array();
                
                // 对授权导航进行过滤
                foreach ($menus['nav_two'] as $k_two => $v_two) {
                    if (! in_array($v_two['menu_id'], $user_auth_amend)) {
                        unset($menus['nav_two'][$k_two]);
                        continue;
                    }
                    
                    if (! in_array($v_two['pid'], $top_existent)) {
                        $top_existent[] = $v_two['pid'];
                    }
                }

                // 排除无下级导航的一级菜单
                foreach ($menus['nav_top'] as $k_top => $v_top) {
                    if (! in_array($v_top['menu_id'], $top_existent)) {
                        unset($menus['nav_top'][$k_top]);
                    }
                }
            }

            return $menus;
        }
        
        return false;
    }
}

// End ^ Native EOL ^ UTF-8