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
 * 获取账户余额
 */
class Api_Cas_GetUserBalance
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
		        if(!empty($userExists)){
		            $res['data']['asset'] = $userExists[0]['asset'];
		            if(!empty($userExists[0]['pay_pwd'])){
		                $res['data']['status'] = "1"; // 有交易密码
		            }else{
		                $res['data']['status'] = "2"; // 没有交易密码
		            }
		            if(!empty($userExists[0]['bank_id'])){
		                if($result = Cas_Model_Bank::instance()->fetchByWhere("bank_id = {$userExists[0]['bank_id']} and is_use = 1 and is_del = 0")){
		                    $res['data']['bank_status'] = "3"; // 已绑定银行卡
		                }else{
		                    $res['data']['bank_status'] = "4"; // 未绑定银行卡
		                }
		            }else{
		                $res['data']['bank_status'] = "4"; // 未绑定银行卡
		            }
		            
		            $res['data']['is_invitaiton'] = $userExists[0]['is_invitaiton'];
		            
		            $res['data']['is_ecoman'] = $userExists[0]['is_ecoman'];
		            
		        }else{
		            $res['data'] = array();
		        }
		        
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取账户余额失败。错误信息：' . $e->getMessage();
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
