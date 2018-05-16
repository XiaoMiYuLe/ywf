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
 * 回调
 * @author Administrator
 *
 */
class Api_Pay_TestCallBack
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
			    $set = Support_Reapal_pay_TestCallBack::run($res['data']);
			    
            /* 返回错误信息  */
			} catch(Zeed_Exception $e) {
				$res['status'] = 1;
				$res['error'] = '错误信息：' . $e->getMessage();
				return $res;
			}  catch(Exception $e) {
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
	    //充值金额默认为1
	    $params['total_fee'] = '1000';
	    /* 验证是否有数据  */
		if (!isset($params['order_no']) || !$params['order_no']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '订单编号未提供';
			return self::$_res;
		}
		if (!isset($params['total_fee']) || !$params['total_fee']) {
		    self::$_res['status'] = 1;
		    self::$_res['error'] = '支付金额未提供';
		    return self::$_res;
		}
		
		
		self::$_res['data'] = $params;
		return self::$_res;
	}
}