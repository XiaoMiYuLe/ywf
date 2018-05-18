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
 * @since      2010-8-16
 * @version    SVN: $Id$
 */

/**
 * 节点站点身份认证
 *
 * @author Nroe
 */
class Com_Authorization_Node extends Com_Authorization
{
    public static function forceLogin($continue = null)
    {
        $moduleName = Zeed_Controller_Request::instance()->getModuleName();
        $moduleUrl = Zeed_Config::loadGroup('urlmapping.' . strtolower($moduleName));

        if (parent::getLoggedInUserid() < 1) {
            /**
             * 转向 CAS 进行身份验证
             */
            $loginUrl = ($moduleName == 'default') ? '/' : "/{$moduleName}/";
            if ($continue != null) {
                $loginUrl .= 'sign/in?continue=' . urlencode($continue);
            } else {
                $loginUrl .= 'sign/in?continue=' . strval($moduleUrl) . urlencode(Zeed_Controller_Request::instance()->requestUri());
            }
            $redirector = new Zeed_Util_Redirector('Goto', $loginUrl, 0, '您的浏览器不支持自动跳转，请手动点击这里');
            $redirector->output();
            exit();
        }
    }

    /**

     * 用户登出

     *

     * @param string $continue

     * @return string

     */
    public static function forceLogout($continue = null)
    {
        if (parent::getLoggedInUserid() > 0) {
            
            $moduleName = Zeed_Controller_Request::instance()->getModuleName();
            $moduleUrl = Zeed_Config::loadGroup('urlmapping.' . strtolower($moduleName));
            $loginUrl = ($moduleName == 'default') ? '/' : "/{$moduleName}/";
            if ($continue != null) {
                $loginUrl .= 'sign/out?continue=' . urlencode($continue);
            } else {
                $loginUrl .= 'sign/out?continue=' . strval($moduleUrl) . urlencode($instance->getInput()->requestUri());
            }
            $redirector = new Zeed_Util_Redirector('Goto', $loginUrl, 0, '您的浏览器不支持自动跳转，请手动点击这里');

            $redirector->output();
            exit();
        } else {
            return false;
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
        $_SESSION['userinfo'] = array(
                'userid' => $user['userid'],
                'username' => $user['username'],
                'nickname' => $user['nickname'],
                'gender' => $user['gender'],
                'ctime' => $user['ctime']);
    }

    /**
     * 获取当前登录用户的基本信息
     *
     * @return array|NULL
     */
    public static function getLoggedInUserInfo()
    {
        return isset($_SESSION['userinfo']) ? $_SESSION['userinfo'] : NULL;
    }
}

// End ^ Native EOL ^ encoding
