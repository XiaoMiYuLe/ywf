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
 * 撤销订单
 */
class Api_Order_CancelOrder
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
            	/*判断订单是否存在*/
                if (!$order = Bts_Model_Order::instance()->fetchByWhere("order_id = {$res['data']['order_id']} and is_del = 0")) {
                	throw new Zeed_Exception('该订单不存在或已被删除');
                }
                
                /*判断订单是否已经付款*/
                if ($order[0]['is_pay'] == 1) {
                	throw new Zeed_Exception('该订单已付款，不能执行此操作');
                }
                
                /*撤销订单*/
               	$result = Bts_Model_Order::instance()->update(array('is_del' => 1),"order_id = {$res['data']['order_id']}");
               	if (!$result) {
               		throw new Zeed_Exception('撤销订单失败');
               	} else {
               		/*如果该订单使用了优惠券，则取消优惠券关系*/
               		if ($order[0]['is_voucher'] == 1 && $order[0]['voucher']) {
               			$data['voucher_status'] = 1;
               			$data['order_id'] = '';
               			Cas_Model_User_Voucher::instance()->update($data,"id = {$order[0]['voucher']}");
               		}
               		/*将订单关联商品的库存加上去*/
               		if ($goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$order[0]['goods_id']}")) {
               			$list['spare_fee'] = $goods[0]['spare_fee'] + $order[0]['buy_money'];
               			Goods_Model_List::instance()->update($list,"goods_id = {$order[0]['goods_id']}");
               		}
               	}
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '撤销订单出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            
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
    	if (!$params['order_id'] || strlen($params['order_id'])<1) {
    		self::$_res['status'] = 1;
    		self::$_res['error'] = '参数订单ID order_id 未提供';
    		return self::$_res;
    	}
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
