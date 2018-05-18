<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
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

class Trend_Authorization
{
    
    /**
     * 获取当前登录用户ID
     *
     * @return integer
     */
    public static function getLoggedInUserid()
    {
        return isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
    }
    
    /**
     *
     * @return string
     */
    public static function getLoggedInUsername()
    {
        return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }
    
    /**
     * 获取当前登录用户的基本信息
     *
     * @return array NULL
     */
    public static function getLoggedInUserInfo()
    {
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;
        if ($userid < 1) {
            return null;
        }
        
        // 取用户信息
        return Sso_Client_Api_User::getUserInfoByUserid($userid);
    }
}

// End ^ Native EOL ^ UTF-8