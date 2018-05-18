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
 * @author     Ahdong ( GTalk: ahdong.com@gmail.com )
 * @since      May 19, 2010
 * @version    SVN: $Id: Authorization.php 11362 2011-08-25 06:36:38Z nroe $
 */

class Com_Authorization
{
    public static function forceLogin($msg = false)
    {
        if (self::getLoggedInUserid() < 1) {
            $url = '/sign/in?continue=' . urlencode($_SERVER["REQUEST_URI"]);
            if ($msg) {
                $sig = md5($msg . '@Wang#Wu#Wang@');
                $url .= '&msg=' . urlencode($msg) . '&sig=' . $sig;
            }
            header('Location: ' . $url);
            exit();
        }
    }
    
    /**
     * 登录用户，记录用户SESSION
     *
     * @param $user
     */
    public static function logInUser($user)
    {
        $_SESSION['userid'] = $user['userid'];
        $_SESSION['username'] = $user['username'];
    }
    
    /**
     * 判断用户是否在线
     * 用户标识 $userid 是 BigInteger 不能使用大于 0 来进行判断用户是否登录
     *
     * @param BigInteger $userid 检查指定用户是否登录
     * @todo param $userid
     */
    public static function isUserLoggedIn($userid = null)
    {
        return (isset($_SESSION['userid']) && ($_SESSION['userid'] > 0));
    }
    
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
     * 获取当前登录用户的基本信息
     *
     * @return array|NULL
     */
    public static function getLoggedInUserInfo()
    {
        if (isset($_SESSION['userid']) && $_SESSION['userid'] > 0) {
            $userinfo = Cas_Model_User_Detail::instance()->getUserByUserid($_SESSION['userid']);
            if (null !== $userinfo) {
                return $userinfo;
            }
        }
        
        return null;
    }
}
