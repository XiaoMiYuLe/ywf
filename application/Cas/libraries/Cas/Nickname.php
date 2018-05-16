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
 * @category   Cas
 * @package    Cas_Nickname
 * @subpackage Cas_Nickname
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @version    SVN: $Id: Nickname.php 11108 2011-08-09 02:33:42Z nroe $
 */

class Cas_Nickname
{
    /**
     *
     *
     * @param BigInteger $userIntID
     * @param string $nickname
     *
     * @return boolean|array 如果成功
     */
    public static function setup($userIntID, $nickname)
    {
        $vaildResult = Cas_Validator::nickname($nickname);
        if (is_array($vaildResult)) {
            $errorMessage = array_pop($vaildResult);
            return array('10043' => $errorMessage);
        }

        $userInfo = Cas_Model_User_Detail::instance()->getUserByUserid($userIntID);

        if (null === $userInfo) {
            return array('10001' => '用户不存在');
        }

        if (strlen($userInfo['nickname'])) {
            return array('10041' => '该用户已设置昵称');
        }

        $Cas_Model_User = Cas_Model_User::instance();
        if ($Cas_Model_User->isNicknameExistent($nickname)) {
            return array('10042' => '该昵称已被占用');
        }

        if (Cas_Model_User::instance()->modifyNickname($userIntID, $nickname)) {
            // 更新 SESSION 记录
            $_SESSION['nickname'] = $nickname;
            return true;
        }

        return array('9' => '未知错误');
    }
}
