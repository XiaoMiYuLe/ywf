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
 * 获取用户可用代金券
 */
class Api_Cas_GetJudgeVoucher
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
		        
		        /* 查询条件 */
		        $where = "userid = {$res['data']['userid']} AND voucher_status = 1";
		       
		        $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere($where, null, null, null);
        	    unset($voucher[0]['userid']);
        	    unset($voucher[0]['order_id']);
        	    unset($voucher[0]['voucher_status']);
        	    unset($voucher[0]['valid_data']);
        	    unset($voucher[0]['use_time']);
        	    unset($voucher[0]['creat_time']);
        	    unset($voucher[0]['disabled']);
        	    if($voucher[0]['id']){
		        $voucher[0]['voucher_id'] = $voucher[0]['id'];
		        unset($voucher[0]['id']);
        	    }
		        
		        $res['data'] = $voucher?$voucher:array();
		        
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取用户代金券失败。错误信息：' . $e->getMessage();
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
