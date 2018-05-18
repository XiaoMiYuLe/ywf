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
 *绑卡签约接口
 * @author Administrator
 *
 */
class Api_Pay_BindCard
{
    /**
     * 默认返回数据
     */
	protected static $_res = array('status' => 0, 'msg' => '', 'data' => '');
	
    public static function run($params = null)
    {
        /* 验证是否有数据  */
		$res = self::validate($params);
		if ($res['status'] == 0) {
			try {
			    /* 检查用户是否存在 */
			    $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['member_id']}'");
			    if (!$userExists) {
			        throw new Zeed_Exception('该用户不存在');
			    }
			    
			    /* 检查用户状态 */
			    if($userExists[0]['status'] == 1 ){
			        throw new Zeed_Exception('该账号已禁用');
			    }
			    
			    $userExists = current($userExists);
			    
			    /*绑定银行卡信息*/
			    if(!empty($userExists)){
			        if(!empty($userExists['bank_id'])){
			            $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$userExists['bank_id']}' and is_use=1 and is_del=0");
			            if(!empty($bank_info)){
			                $res['data']['bind_id'] = $bank_info[0]['bind_id'];
			            }
			        }
			    }
			    
			    if(empty($res['data']['bind_id'])){
			        throw new Zeed_Exception('未绑卡');
			    }
			    
			    $set = Support_Reapal_bindcardportal_bindCardResult::run($res['data']);
			    if ($set['status'] != 0000) {
			    	throw new Zeed_Exception($set['error']);
			    }
			    $res['data']['bank_code'] = $set['data']->bank_code;
			    $res['data']['bank_name'] = $set['data']->bank_name;
			    $res['data']['merchant_id'] = $set['data']->merchant_id;
			    $res['data']['order_no'] = $set['data']->order_no;
			    $res['data']['result_code'] = $set['data']->result_code;
			    $res['data']['bank_code'] = $set['data']->result_ms;
			    $res['error'] = $set['error'];
			    
            /* 返回错误信息  */
			} catch(Zeed_Exception $e) {
				$res['status'] = 1;
				$res['error'] = '错误信息：' . $e->getMessage();
				return $res;
			} catch(Exception $e) {
				$res['status'] = 1;
				$res['error'] = '错误信息：网络请求异常，请稍后再试';
				return $res;
			}
		}
		/* 返回数据 */
		return $res;
	}
	
	/**
	 * 验证参数
	 */
	public static function validate($params)
	{
	    /* 验证是否有数据  */
		if (!isset($params['merchant_id']) || ! $params['merchant_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户号未提供';
			return self::$_res;
		}
		if (!isset($params['order_no']) || ! $params['merchant_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户订单号';
			return self::$_res;
		}
	   if (!isset($params['member_id']) || ! $params['merchant_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '会员号未提供';
			return self::$_res;
		}
		if (!isset($params['total_fee']) || ! $params['merchant_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '交易金额未提供';
			return self::$_res;
		}
		self::$_res['data'] = $params;
		return self::$_res;
	}
}