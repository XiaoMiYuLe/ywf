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
 * @since      2011-5-24
 * @version    SVN: $Id$
 */

class Cas_Authorization
{
    /**
     * 登录用户，记录用户SESSION
     *
     * @param $user
     */
    public static function logInUser($user, $lastLogin = null)
    {
        $_SESSION['userid'] = $user['userid'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nickname'] = isset($user['nickname']) ? $user['nickname'] : NULL;
        $_SESSION['lastLogin'] = isset($lastLogin['last_login_time']) ? $lastLogin['last_login_time'] : NULL;
        $_SESSION['lastLoginIp'] = isset($lastLogin['last_login_ip']) ? $lastLogin['last_login_ip'] : NULL;
        
        $_SESSION['third_username'] = isset($user['third_username']) ? $user['third_username'] : NULL;
        $_SESSION['third_domain'] = isset($user['third_domain']) ? $user['third_domain'] : NULL;
    }
    
    /**
     * 获取第三方用户域名
     */
    public static function getThirdUserDomain()
    {
        if (isset($_SESSION['third_domain'])) {
            return $_SESSION['third_domain'];
        }
        
        return null;
    }
    
    /**
     * 获取第三方用户名，如YY号，畅游ID
     */
    public static function getThirdUsername()
    {
        if (isset($_SESSION['third_username'])) {
            return $_SESSION['third_username'];
        }
        
        return null;
    }
    
    /**
     * 判断用户是否在线
     */
    public static function isUserLoggedIn()
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
     * 获取当前登录用户名
     *
     * @return string
     */
    public static function getLoggedInUsername()
    {
        return isset($_SESSION['username']) ? $_SESSION['username'] : NULL;
    }
    
    /**
     * 获取当前登录用户昵称
     *
     * @return string
     */
    public static function getLoggedInUserNickname()
    {
        return isset($_SESSION['nickname']) ? $_SESSION['nickname'] : NULL;
    }
    
    /**
     * 获取当前登录用户的基本信息
     *
     * @return array|NULL
     */
    public static function getLoggedInUserInfo()
    {
        if (isset($_SESSION['userid']) && $_SESSION['userid'] > 0) {
            $phone = $_SESSION['phone'];
            $newString = substr_replace($phone, "****", 3, 4);
            
            $userEx = Cas_Model_User::instance()->fetchByWhere("userid = {$_SESSION['userid']} and status = 0",array('is_invitaiton','is_ecoman','idcard'));
            if ($userEx) {
                $is_invitaiton = $userEx[0]['is_invitaiton'];
                $is_ecoman = $userEx[0]['is_ecoman'];
                $idcard = $userEx[0]['idcard'];
            }
            
            $userinfo = array(
                'userid' => $_SESSION['userid'],
                'username' => $_SESSION['username'],
                'phone' => $phone,
                'hidephone' => $newString,
                'is_invitaiton' =>$is_invitaiton, //申请状态
                'is_ecoman' => $is_ecoman, //是否是经纪人
                'is_validate' => $idcard==''?0:1, //是否绑卡
            );
            
            if (null !== $userinfo) {
                return $userinfo;
            }
        }
        return null;
    }
    
    /**
     * 强制用户登录
     * 
     * @param string $msg
     */
    public static function forceLogin($msg = false)
    {
        if (self::getLoggedInUserid() < 1) {
            $url = '/cas/sign/in?continue=' . urlencode($_SERVER["REQUEST_URI"]);
            if ($msg) {
                $sig = md5($msg . '@Wang#Wu#Wang@');
                $url .= '&msg=' . urlencode($msg) . '&sig=' . $sig;
            }
            header('Location: ' . $url);
            exit();
        }
    }
    
    /**
     * 强制商户登录
     * 
     * @param string $msg
     */
    public static function forceLoginStore($msg = false)
    {
        if (self::getLoggedInUserid() < 1) {
            $url = '/storecas/sign/in?continue=' . urlencode($_SERVER["REQUEST_URI"]);
            if ($msg) {
                $sig = md5($msg . '@Wang#Wu#Wang@');
                $url .= '&msg=' . urlencode($msg) . '&sig=' . $sig;
            }
            header('Location: ' . $url);
            exit();
        }
    }
}

// End ^ Native EOL ^ encoding
