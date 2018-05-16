<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-26
 * @version SVN: $Id$
 */

/**
 * token相关类
 */
class Cas_Token
{

    public static $token_time = 31536000;

    /**
     * 根据token获取用户ID
     *
     * @param string $token 推送标题
     * @return int|0:查找失败
     */
    public static function getUserIdByToken($token)
    {
        if (! $token || ! is_string($token)) {
            return 0;
        }

        $userId = Cas_Model_Token::instance()->fetchByFV('user_token', $token, array('userid'));

        if (! $userId) {
            return 0;
        }

        return (int)$userId[0]['userid'];
    }

    /**
     * 根据用户ID获取token
     *
     * @param string $userid 推送标题
     * @return string|0:查找失败
     */
    public static function getTokenByUserId($userid)
    {
        if (! $userid) {
            return 0;
        }

        $token = Cas_Model_Token::instance()->fetchByFV('userid', $userid, array('user_token'));
        if (! $token) {
            return 0;
        }

        return $token[0]['user_token'];
    }

    /**
     * 判断token是否过期
     *
     * @param string $token 推送标题
     * @return bool
     */
    public static function isTokenTime($token)
    {
        $nowDate = date('Y-m-d H:i:s');
        $eTime = Cas_Model_Token::instance()->fetchByFV('user_token', $token, array('etime'));
        if (! $eTime[0]['etime']) {
            return false;
        }

        if (strtotime($eTime[0]['etime']) > strtotime($nowDate)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 更新token
     *
     * @param integer $userid 用户ID
     * @return null|string
     */
    public static function updateToken($userid)
    {
        $nowDate = date('Y-m-d H:i:s');
        if (! $userid) {
            return null;
        }

        $token_time = Zeed_Config::loadGroup('token.token_time');
        if (! $token_time) {
            $token_time = self::$token_time;
        }

        $token = md5(Zeed_Util::getRandomString() . $userid);
        $set['user_token'] = $token;
        $set['mtime'] = $nowDate;
        $set['etime'] = date('Y-m-d H:i:s', strtotime($nowDate) + $token_time);
        $res = Cas_Model_Token::instance()->updateForEntity($set, $userid);
        return $res ? $token : null;
    }

    /**
     * 为用户创建token
     *
     * @param integer $userid 用户ID
     * @return null|string
     */
    public static function initToken($userid)
    {
        $nowDate = date('Y-m-d H:i:s');
        if (! $userid) {
            return null;
        }

        $token_time = Zeed_Config::loadGroup('token.token_time');
        if (! $token_time) {
            $token_time = self::$token_time;
        }

        $token = md5(Zeed_Util::getRandomString() . $userid);

        $set['user_token'] = $token;
        $set['userid'] = $userid;
        $set['ctime'] = $nowDate;
        $set['mtime'] = $nowDate;
        $set['etime'] = date('Y-m-d H:i:s', strtotime($nowDate) + $token_time);
        $res = Cas_Model_Token::instance()->addForEntity($set, $userid);
        return $res ? $token : null;
    }
}

// End ^ Native EOL ^ UTF-8