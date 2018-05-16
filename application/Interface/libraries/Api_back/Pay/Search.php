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
 *重发短信验证码接口
 * @author Administrator
 *
 */
class Api_Pay_Search
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
				$order = Cas_Model_Pay::instance()->fetchByWhere("order_no = '{$res['data']['order_no']}'");
				$order = $order[0];
				
				//银行异步通知，支付不成功状态返回值
				if($order['code']!=0 &&$order['code']!=0000){
					$res['status'] = 2;
					$res['error'] = $order['msg'];
					return $res;
				}
			    $set = Support_Reapal_search_searchResult::run($res['data']);
			    if ($set['status'] != 0000) {
			    	throw new Zeed_Exception($set['error']);
			    } else{
			        
			        if((!empty($order)) && ($order['type']==1)){
			            $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$order['userid']}' and status=0");
			            $bank = Cas_Model_Bank::instance()->update(array('is_use'=>1),"bank_id='{$ExistUser[0]['bank_id']}' and is_del=0");
			        }elseif((!empty($order)) && ($order['type']==2)){
			        	$paytime = date('Y-m-d H:i:s');
			        	$update_order = Bts_Model_Order::instance()->update(array('is_pay'=>1,'pay_time'=>$paytime,'pay_type'=>2,'is_del'=>0,'order_status'=>2),"order_no = '{$order['order_no']}' and is_del=1 and is_pay=0");
			        	//代金券修改
			        	$goods_order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order['order_no']}'");
			        	$goods_order = $goods_order[0];
			        	//代金券状态处理 已使用:voucher_status=2
			        	if($goods_order['is_voucher']==1){
			        	    $use_time = date(DATETIME_FORMAT);
			        	    $voucher_status = Cas_Model_User_Voucher::instance()->update(array('voucher_status'=>2,'order_id'=>"{$goods_order['order_id']}",'use_time'=>"{$use_time}"),"id={$goods_order['voucher']}");
			        	}
			        }
			    	$set['error'] = '支付成功';
			    }
			    $res['data'] = $set['data'];
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
		if (!isset($params['merchant_id']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户号未提供';
			return self::$_res;
		}
		if (!isset($params['order_no']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户订单号';
			return self::$_res;
		}
	
	
		
		self::$_res['data'] = $params;
		return self::$_res;
	}
}