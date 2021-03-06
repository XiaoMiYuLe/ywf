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
 * 修改交易密码
 */
class Api_Cas_ResetPaypwd
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_allowFields = array('userid','oldpay_pwd','pay_pwd','repeatpay_pwd');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        	    /*密码一致性*/
        	    if($res['data']['pay_pwd'] !=$res['data']['repeatpay_pwd']){
        	        throw new Zeed_Exception('两次密码输入不一致');
        	    }
        	    
        	    /* 检查用户是否存在 */
        	    if (! $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
        	        throw new Zeed_Exception('该用户不存在');
        	    }
        	    
        	    $userExists = current($userExists);
        	    
        	    //检查用户是否被冻结
        	    if($userExists['status']==1){
        	        throw  new Zeed_Exception('该用户被冻结');
        	    }

        	    /*原始交易密码是否正确*/
        	    //加密
        	    $oldpay_pwd= md5($res['data']['oldpay_pwd']);
        	    if($oldpay_pwd !=$userExists['pay_pwd']){
        	        throw new Zeed_Exception('原始交易密码不正确');
        	    }
        	    
        	    /*交易密码不能与登入密码相同*/
        	    //加密
        	    $salt=$userExists['salt'];
        	    $pay_pwd = self::encryptpwd($res['data']['pay_pwd'],$salt);
        	    
        	    
        	    if($pay_pwd ==$userExists['password']){
        	        throw new Zeed_Exception('交易密码不能与登入密码相同');
        	    }
        	    
        		//交易密码加密
        		$data['userid'] = $res['data']['userid'];
    			$data['pay_pwd'] = md5($res['data']['pay_pwd']);
    			$data['mtime'] = date('Y-m-d H-i-s');
    			
        		//修改交易密码
                $rs = Cas_Model_User::instance()->updateForEntity($data, $res['data']['userid']);
                if(!empty($rs)){
                    $res['data'] = $data;
                    $res['status'] = 0;
                    $res['msg'] = '交易密码修改成功';
                    return $res;
                }else{
                    throw new Zeed_Exception('交易密码修改失败');
                } 
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '交易密码修改失败。错误信息：' . $e->getMessage();
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
            self::$_res['error']  = '用户id未提供';
            return self::$_res;
        }
        
        if (! isset($params['pay_pwd']) || ! $params['pay_pwd']) {
    		self::$_res['status'] = 1;
    		self::$_res['error']  = '交易密码必须提供';
    		return self::$_res;
    	}
    	
    	if (! isset($params['repeatpay_pwd']) || ! $params['repeatpay_pwd']) {
    		self::$_res['status'] = 1;
    		self::$_res['error']  = '确认密码必须提供';
    		return self::$_res;
    	}
    	
    	if (! isset($params['repeatpay_pwd']) || strlen($params['repeatpay_pwd']) < 6) {
    		self::$_res['status'] = 1;
    		self::$_res['error']  = '确认密码必须提供，且长度至少为6位';
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
    
    /*
     * 交易密码处
     */
    public static function encryptpwd($str='',$salt='')
    {
        return MD5($str.$salt);
    }
}

// End ^ Native EOL ^ encoding
