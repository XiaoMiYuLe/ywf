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
 *是否卡密可用
 * @author Administrator
 *
 */
class Api_Pay_Iskamiok
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
				$where['order_no'] = $res['data']['order_no'];
				$order = 'id DESC';
			    $set = Cas_Model_Kami::instance()->fetchByWhere($where,$order);
			    
			    if (!$set[0]['result_code']) {
			    	//没有收到融宝的异步通知
			    	throw new Zeed_Exception('没有异步通知');
			    } elseif($set[0]['result_code']==='0000'){
			    	//收到融宝的异步通知（成功状态）
                    $res['status'] = 0;
                    $res['error'] = $set[0]['result_msg'];
                    return $res;
			    }else{
			    	//收到融宝的异步通知（失败状态）
                    $res['status'] = $set[0]['result_code'];
                    $res['error'] = $set[0]['result_msg'];
                    return $res;
			    }
			    
            /* 返回错误信息  */
			} catch(Zeed_Exception $e) {
				$res['status'] = 1;
				$res['error'] = '错误信息：' . $e->getMessage();
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
		if (!isset($params['order_no']) ) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户订单号';
			return self::$_res;
		}
	
	
		
		self::$_res['data'] = $params;
		return self::$_res;
	}
}