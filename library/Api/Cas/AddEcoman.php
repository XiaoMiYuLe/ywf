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
 * 添加经纪人
 */
class Api_Cas_AddEcoman
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_allowFields = array('userid');
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
                $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['userid']}'");
    	        if (!$userExists) {
    	            throw new Zeed_Exception('该用户不存在，请重新输入');
    	        }
    	        
    	        /* 检查用户状态 */
    	        if($userExists[0]['status'] == 1 ){
    	            throw new Zeed_Exception('该账号已禁用，请重新输入');
    	        }
    	        
    	        $userExists = current($userExists);
    	        
    	        if($userExists['is_ecoman']==1){
    	            throw new Zeed_Exception('您已经是经纪人');
    	        }
    	        
    	        //判断上限是否为经纪人，若不是解除上一级关系
    	        if($userExists['parent_id']){
    	            $parent_user = Cas_Model_User::instance()->fetchByWhere("userid = {$userExists['parent_id']}");
    	            if($parent_user[0]['is_ecoman']==0){
    	                $delete_parent = Cas_Model_User::instance()->update(array('parent_id'=>null),"userid={$res['data']['userid']}");
    	            }
    	        }
    	        
    	        $ecoman = Cas_Model_User::instance()->update(array('is_ecoman'=>'1'),"userid={$res['data']['userid']}");
    	        if(empty($ecoman)){
    	           throw new Zeed_Exception('申请经纪人失败');
    	        }
    	        
    	        $data['userid'] = $res['data']['userid'];
    	        
		        $res['data'] = $data;
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '申请经纪人。错误信息：' . $e->getMessage();
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
        /* 校验必填项 */
        if (! isset($params['userid']) || ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '用户id未提供';
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
