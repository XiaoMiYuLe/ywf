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
 * 卡密接口
 * @author Administrator
 *
 */
class Api_Pay_Certificate
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
			    $set = Support_Reapal_certificate_certificateResult::run($res['data']);
			    //成功
// 			    $set1['news_title'] = $set['data'];
// 			    $set1['ctime'] = date(DATETIME_FORMAT);
// 			    $set1['news_content'] = $set['data'];//返回码
// 			    News_Model_List::instance()->insert($set1);
			     
			    $res['data'] = $set['data'];
			    return $res;
			   
			    
            /* 返回错误信息  */
			} catch(Zeed_Exception $e) {
				$res['status'] = 1;
				$res['error'] = '储蓄卡签约失败。错误信息：' . $e->getMessage();
				return $res;
			}  catch(Exception $e) {
				$res['status'] = 1;
				$res['error'] = '错误信息：网络请求异常，请稍后再试';
				return $res;
			}
		}
// 		var_dump($res);
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
		if (!isset($params['bind_id']) || ! $params['bind_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '绑卡号未提供';
			return self::$_res;
		}
		if (!isset($params['order_no']) || ! $params['order_no']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '订单号未提供';
			return self::$_res;
		}
		if (!isset($params['member_id']) || ! $params['member_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '会员号未提供';
			return self::$_res;
		}
		if (!isset($params['return_url']) || ! $params['return_url']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '返回url未提供';
			return self::$_res;
		}
	
		
		self::$_res['data'] = $params;
		return self::$_res;
	}
}