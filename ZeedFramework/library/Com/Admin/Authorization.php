<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Ahdong ( GTalk: ahdong.com@gmail.com )
 * @since      Nov 10, 2010
 * @version    SVN: $$Id$$
 */

/**
 * 管理员认证类
 */
class Com_Admin_Authorization
{
    /**
     * 强制用户登录
     * @param $msg
     */
    public static function forceLogin($msg = false)
    {
        if (! self::getLoggedInUser()) {
            $url = ($_SERVER['SERVER_PORT'] == "443") ? 'https' : 'http';
            $url .= '://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            $location = Zeed_Config::loadGroup('access.login_url').'continue='.urlencode($url);
            if ($msg) {
                $location .= '&msg=' . urlencode($msg);
            }
            header('Location: '.$location);
            exit();
        }
    }
    /**
     * 获取登录用户信息
     * 
     * @return array|false
     */
    public static function getLoggedInUser()
    {
        return isset($_SESSION['adminuser']) ? $_SESSION['adminuser'] : false;
    }
    
    /**
     * 记录登录用户信息，标记用户为已登录
     * @param $user
     */
    public static function logUserIn($user)
    {
        $_SESSION['adminuser'] = $user;
    }
    
    /**
     * 删除SESSION COOKIE
     */
    public static function logUserOut()
    {
        Zeed_Session::destroy(true);
    }
}

// End ^ LF ^ encoding
