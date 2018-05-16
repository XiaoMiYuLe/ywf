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
 * 获取订单详情
 */
class Api_Order_GetOrderDetail
{

    /**
     * 返回参数
     */
    protected static $_res = array(
            'status' => 0,
            'error' => '',
            'data' => ''
    );

    /**
     * 接口运行方法
     *
     * @param string $params            
     * @throws Zeed_Exception
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
    public static function run ($params = null)
    {
        // 执行参数验证
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            try {
				if (!$order = Bts_Model_Order::instance()->fetchByWhere("order_id = {$res['data']['order_id']} and is_del = 0")) {
					throw new Zeed_Exception('该订单不存在或已被删除');
				} else {
					foreach ($order as $k => &$v) {
						$v['buy_money'] = number_format($v['buy_money'],2);
						$v['real_money'] = number_format($v['real_money'],2);
						$v['bts_yield'] = number_format($v['bts_yield'],2);
					}
				}
            	
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取订单详情出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            self::$_res['data'] = $order[0];
        }
        return self::$_res;
    }

    /**
     * 验证参数
     *
     * @param array $params            
     * @throws Zeed_Exception
     */
    public static function validate ($params)
    {
    	/*校验参数*/
        if (! isset($params['order_id']) || strlen($params['order_id']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数订单ID order_id 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
