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
 * @since      2011-3-21
 * @version    SVN: $Id$
 */
class Api_Cas_Login
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array('phone','password');
	/* 根据用户手机号登录 */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] === 0) {
            try {
            /* 判断是否传手机号 */
            Util_Validator::test_mobile(trim($res['data']['phone']),"请填写正确的手机号码");
            
            /* 判断是否传password */
            Util_Validator::test_pwd(trim($res['data']['password']),"密码必须为6-16位，同时包含数字和字母两种,777");

            /* 检查用户是否存在 */
            $userExists = Cas_Model_User::instance()->fetchByWhere( "phone= '{$res['data']['phone']}'");
            //echo (Cas_Model_User::instance()->getAdapter()->getProfiler()->getLastQueryProfile()->getQuery());
	        if (!$userExists) {
	            throw new Zeed_Exception('该用户不存在，请重新输入');
	        }
	        
	        /* 检查用户状态 */
	        if($userExists[0]['status'] == 1 ){
	            throw new Zeed_Exception('该账号已禁用，请重新输入');
	        }
            
	        /*传进来的密码进行加密*/
	        $salt = $userExists[0]['salt'];
	        $password = self::encrypt($res['data']['password'],$salt);
	        
	        /* 检查用户密码 */
	        if ($password!=$userExists[0]['password']) {
	            throw new Zeed_Exception('用户名或密码不正确');
	        }
	        
	        /* 密码正确，返回用户基本信息 */
	        $res['data'] = $userExists;
	    } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = "登入失败。错误信息：" . $e->getMessage();
                return $res;
            }
        }
        
        return $res;
    }
    
    
    /**
     * 验证参数
     */
    public static function validate ($params)
    {
        if (! isset($params['phone']) || ! $params['phone']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 phone未提供';
            return self::$_res;
        }
        if (! isset($params['password']) || !$params['password']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 password未提供';
            return self::$_res;
        }
        /* 组织数据 */
        $set = array();
        foreach (self::$_allowFields as $f) {
            $set[$f] = isset($params[$f]) ? $params[$f] : null;
        }
        self::$_res['data'] = $set;
        
        return self::$_res;
    }
     /*
     * 加密算法
     */
    public static function encrypt($str='',$salt='')
    {
        return MD5(MD5($str).$salt);
    }
}