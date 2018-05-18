<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Cas
 * @package    Cas_Nickname
 * @subpackage Cas_Nickname
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @version    SVN: $Id$
 */

class Store_Helper_User
{
    private static $_default_encrypt = 'Md5Md5'; // 定义用户密码的加密方式
    private static $_default_status = 1; // 定义默认启用状态为开启
    private static $_default_is_verify = 1; // 定义默认审核状态为通过
    private static $_user_group_id = 2; // 定义用户默认分组为商户组
    private static $_user_lv_id = 5; // 定义用户默认级别为初级企业会员
    
    /**
     * 创建用户
     *
     * @param array $params
     * @return boolean|array
     */
    public static function register($set)
    {        
        $passwordSalt = Zeed_Encrypt::generateSalt();
        
        if (empty($set['username']) || empty($set['password'])) {
        	return false;
        }
        $password = Zeed_Encrypt::encode(self::$_default_encrypt, $set['password'], $passwordSalt);
        
        $timenow = DATENOW;
        $ip = Zeed_Util::clientIP();
        $userSet = array(
        		'username' => $set['username'],
        		'user_group_id' => isset($set['user_group_id']) ? $set['user_group_id'] : self::$_user_group_id,
        		'user_lv_id' => isset($set['user_lv_id']) ? $set['user_lv_id'] : self::$_user_lv_id,
        		'password' => $password,
        		'phone' => $set['phone'],
        		'salt' => $passwordSalt,
        		'encrypt' => self::$_default_encrypt,
        		'email' => $set['email'],
        		'realname' => $set['realname'],
        		'idcard' => $set['idcard'],
        		'gender' => $set['gender'],
        		'birthday' => $set['birthday'],
        		'region_id' => $set['region_id'],
        		'address' => $set['address'],
        		'status' => isset($set['status']) ? $set['status'] : self::$_default_status,
        		'is_verify' => isset($set['is_verify']) ? $set['is_verify'] : self::$_default_is_verify,
        		'ctime' => $timenow,
        		'mtime' => $timenow,
        		'last_login_time' => $timenow,
        		'last_login_ip' => $ip,
        		'company_name' => $set['company_name']
        );
        
        try {
        	foreach ($userSet as $k => $v) {
        		if (empty($v)) {
        			unset($userSet[$k]);
        		}
        	}
        	$userid = Cas_Model_User::instance()->add($userSet);
        } catch (Zeed_Exception $e) {
            echo $e->getMessage();exit;
        	return false;
        }        
        return $userid;        
    }
    
    /**
     * 根据用户 ID 删除用户（暂不启用）
     * 
     * @param integer $userid
     * @return boolean
     */
    public static function delete($userid)
    {
        try {
            if (Cas_Model_User::instance()->deleteByPK($userid)) {
                if (Cas_Model_User_Detail::instance()->deleteByPK($userid)) {
                    return true;
                }
            } else {
                return false;
            }
        
        } catch (Zeed_Exception $e) {
            return false;
        }
        return true;
    }
}
