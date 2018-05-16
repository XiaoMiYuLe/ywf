<?php

/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class TestController extends IndexAbstract
{
    /**
     * 
     */
    public function order()
    {
        $this->addResult(self::RS_SUCCESS, 'json');
        $order['order_no'] = '2016062151101971';
        $goods_order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order['order_no']}'");
        $goods_order = $goods_order[0];

        Cas_Model_User_Voucher::instance()->sendCouponByOrder($goods_order['userid'], floatval($goods_order['buy_money']));
    }
    
    public function signup(){
        //发送优惠券
        $userid = 3225;
        Cas_Model_User_Voucher::instance()->sendCoupon(1,$userid);
    }
}

// End ^ Native EOL ^ UTF-8