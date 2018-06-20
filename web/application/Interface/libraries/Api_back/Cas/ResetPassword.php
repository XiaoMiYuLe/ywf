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

/**
 * 重置密码
 */
class Api_Cas_ResetPassword
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_allowFields = array('userid','oldpassword','newpassword');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        	    
        	    /* 判断是否传password */
        	    Util_Validator::test_pwd(trim($res['data']['newpassword']),"密码必须为6-16位，同时包含数字和字母两种，555");
        	    
        		/* 检查用户是否存在 */
        		if (! $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
        		    throw new Zeed_Exception('该用户不存在');
        		}
        		
        		$userExists = current($userExists);
        		
        		//检查用户是否被冻结
        		if($userExists['status']==1){
        		    throw  new Zeed_Exception('该用户被冻结');
        		}
        		
        		/* 检查用户密码 */
        		//加密
        		$salt=$userExists['salt'];
        		$oldpassword = self::encrypt($res['data']['oldpassword'],$salt);
        		
        		
        		if($oldpassword !=$userExists['password']){
        		    throw new Zeed_Exception('原始密码不正确');
        		}
        		//新密码加密
        		$data['salt'] = Zeed_Util::genRandomString(10);
    			$data['encrypt'] = 'Md5Md5';
    			$data['password'] = self::encrypt($res['data']['newpassword'],$data['salt']);
    			$data['mtime'] = date('Y-m-d H:i:s');
    			
        		//重置密码
                $rs = Cas_Model_User::instance()->updateForEntity($data, $res['data']['userid']);
                if(empty($rs)){
                    throw new Zeed_Exception('修改密码失败');
                } 
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '修改密码失败。错误信息：' . $e->getMessage();
        		return $res;
        	}
        }
        return $res;
    }
    
    /**
     * 验证参数
     */
    public static function validate($params)
    {
        if (! isset($params['userid']) || ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 userid未提供';
            return self::$_res;
        }
        
    	if (! isset($params['oldpassword']) || strlen($params['oldpassword']) < 6) {
    		self::$_res['status'] = 1;
    		self::$_res['error']  = '参数 oldpassword 必须提供，且长度至少为6位';
    		return self::$_res;
    	}
    	
    	if (! isset($params['newpassword']) || strlen($params['newpassword']) < 6) {
    		self::$_res['status'] = 1;
    		self::$_res['error']  = '参数 newpassword 必须提供，且长度至少为6位';
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

// End ^ Native EOL ^ encoding
