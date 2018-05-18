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
 * 用户是否需要支付手续费
 */
class Api_Cas_GetWithdrawNumber
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
		        if (! $userExists = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
		            throw new Zeed_Exception('该用户不存在或被冻结');
		        }
		        
		        //处理用户余额    提现每月前2次免费，超过2次每笔2元
		        $nowtime = substr(date("Y-m-d",time()),0,7);
                $nowdate = substr(date("Y-m-d",time()),0,7).'-01';
		        $order = "ctime DESC";
                $where = "userid = {$res['data']['userid']} and ctime>='{$nowdate}'";
		        //$user = Withdraw_Model_List::instance()->fetchByWhere("userid = {$res['data']['userid']} and ctime>='{$nowdate}'",$order,1,0);
                $count = Withdraw_Model_List::instance()->getCount($where);
		        if($count >= 2){
		            $number = '0';//需支付手续费
		        }else{
		            /* 第一次提现  */
		            $number = '1';//不需支付手续费
		        }
		        $res['data']['number'] = $number;
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '提示:' . $e->getMessage();
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
            self::$_res['error']  = '参数 userid 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
