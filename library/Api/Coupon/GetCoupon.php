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
 * 用户领取优惠券
 * @author zhangr01
 */
class Api_Coupon_GetCoupon
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                /* 检查用户是否存在 */
                if (! $userid = Cas_Token::getUserIdByToken($res['data']['token'])) {
                    throw new Zeed_Exception('查无此TOKEN数据');
                }
                if (! Cas_Model_User::instance()->fetchByPK($userid)) {
                    throw new Zeed_Exception('查无此用户信息');
                } 
                /* 检查优惠券状态 */
                $now = date(('Y-m-d H:i:s'), time());
                $where = "coupon_category.coupon_id = " .$res['data']['coupon_id']. " AND disabled = 0 AND status = 1 AND grant_stime < '{$now}' AND grant_etime > '{$now}' AND is_del = 0";
            	if (! $coupon = Coupon_Model_Category::instance()->fetchAvailableCoupon($where)){
            	    throw new Zeed_Exception('非法操作  - 优惠券不可用');
            	}
            	if ($coupon[0]['exchanged_total'] >= $coupon[0]['total']) {
            	    throw new Zeed_Exception('该优惠券已领完');
            	}
            	/* 检查用户状态 */
            	$where = 'userid = ' . $userid . ' AND coupon_id = ' . $res['data']['coupon_id'];
            	if (Coupon_Model_Listing::instance()->fetchByWhere($where)){
            	    throw new Zeed_Exception('您已经领取过该优惠券啦');
            	}
            	/* 领取优惠券 */
            	if (! $info = Coupon_Helper::getCouponByUserid($userid, $res['data']['coupon_id'])){
            	    throw new Zeed_Exception('网络繁忙，请稍后重试');
            	}
                $res['data']['coupon'] = $info;
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '领取优惠券失败。错误信息：' . $e->getMessage();
            }
        }
        return $res;
    }
    
    /**
     * 验证参数
     */
    private static function validate($params)
    {
        try {
            if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])){
                throw new Zeed_Exception('无效的Token');
            }
            if (! $params['coupon_id'] || ! is_numeric($params['coupon_id'])){
                throw new Zeed_Exception('参数优惠券ID错误');
            }
        } catch (Zeed_Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error']  = $e->getMessage();
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
