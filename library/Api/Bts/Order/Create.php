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
 * 下单
 */
class Api_Bts_Order_Create
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');

    /**
     * 接口运行方法
     * 
     * @param string $params
     * @throws Zeed_Exception
     * @return string|Ambigous <string, multitype:number, multitype:number string , unknown, multitype:>
     */
    public static function run ($params = null)
    {
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            /* 生成订单 */
            $res = Bts_Model_Order::instance()->transactionCart($res['data']['cart_ids'],$res['data']['userid'],$res['data']['consignee']);
        }
        
        return $res;
    }

    
    /**
     * 数据校验
     * 
     * @param unknown $params
     * @return multitype:number string
     */
    public static function validate ($params)
    {
        /* 校验参数 */
        if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 token未提供或无效的token';
            return self::$_res;
        }
        $params['userid'] = Cas_Token::getUserIdByToken($params['token']);

        if (! $params['cart_ids']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '不可以提交空的订单';
            return self::$_res;
        }
        
        /* 校验购物车的情况 */
        if ($params['cart_ids']) {
            
        	if (is_string($params['cart_ids'])) {
        		
        		// 多个购物车商品验证正确性，单个购物车ID验证正确性 
        		if (strpos($params['cart_ids'], ',')) {
        			
        			$shopcatrs = explode(',', $params['cart_ids']);
        			
        			foreach ($shopcatrs as $value) {
        			    $catr = Bts_Model_Cart::instance()->fetchByPK($value);
        				if (! $catr) {
        					self::$_res['status'] = 1;
        					self::$_res['error'] = '您提交信息不存在请检查';
        					return self::$_res;
        				}
        			}
        			
        		}else{
        			
        			/* 检查用户是否存在 */
        			if (! $catr = Bts_Model_Cart::instance()->fetchByPK($params['cart_ids'])) {
		        		self::$_res['status'] = 1;
		        		self::$_res['error'] = '您提交信息不存在请检查';
		        		return self::$_res;
        			}
        			
        		}
        		
        	}else{
        		self::$_res['status'] = 1;
        		self::$_res['error'] = '参数格式不正确';
        		return self::$_res;
        	}
        }

        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
