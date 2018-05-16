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
 * 编辑联系方式
 */
class Api_Cas_UpdateInfo
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    protected static $_allowFields = array('userid','address','zip_code','contacts_person','contacts_phone');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        	    
        		/* 检查用户是否存在 */
        		if (! $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
        		    throw new Zeed_Exception('该用户不存在');
        		}
        		
        		$userExists = current($userExists);
        		
        		//检查用户是否被冻结
        		if($userExists['status']==1){
        		    throw  new Zeed_Exception('该用户被冻结');
        		}
		        
        		$data['userid'] = $res['data']['userid'];
        		$data['address'] = $res['data']['address'];
        		$data['zip_code'] = $res['data']['zip_code'];
        		$data['contacts_person'] = $res['data']['contacts_person'];
        		$data['contacts_phone'] = $res['data']['contacts_phone'];
        		
		        /* 更改用户信息 */
		        if (! $userupdate = Cas_Model_User::instance()->updateForEntity($data, $res['data']['userid'])) {
		        	throw new Zeed_Exception('修改联系方式失败');
		        }
		        
		        $res['data'] = $data;
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '修改失败。错误信息：' . $e->getMessage();
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
        
        if (! isset($params['address']) || ! $params['address']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '详细地址未提供';
            return self::$_res;
        }
        
        if (! isset($params['zip_code']) || ! $params['zip_code']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '邮编未提供';
            return self::$_res;
        }

        if (! isset($params['contacts_person']) || ! $params['contacts_person']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '联系人未提供';
            return self::$_res;
        }

        if (! isset($params['contacts_phone']) || ! $params['contacts_phone']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '联系电话未提供';
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
}

// End ^ Native EOL ^ encoding
