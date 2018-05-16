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
 * 信用卡签约接口
 * @author Administrator
 *
 */
class Api_Pay_Credit
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
			    $set = Support_Reapal_credit_creditResult::run($res['data']);
			    $res['data'] = $set['data'];
			    
            /* 返回错误信息  */
			} catch(Zeed_Exception $e) {
				$res['status'] = 1;
				$res['error'] = '信用卡签约失败。错误信息：' . $e->getMessage();
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
		if (!isset($params['merchant_id']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户号未提供';
			return self::$_res;
		}
		if (!isset($params['card_no']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '银行卡号未提供';
			return self::$_res;
		}
		if (!isset($params['owner']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '姓名未提供';
			return self::$_res;
		}
		if (!isset($params['member_id']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '会员号未提供';
			return self::$_res;
		}
		if (!isset($params['cert_no']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '证件号码未提供';
			return self::$_res;
		}
		if (!isset($params['cvv2']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '安全码未提供';
			return self::$_res;
		}
		if (!isset($params['validthru']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '有效期未提供';
			return self::$_res;
		}
		if (!isset($params['phone']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '手机号未提供';
			return self::$_res;
		}
	
		
		self::$_res['data'] = $params;
		return self::$_res;
	}
}