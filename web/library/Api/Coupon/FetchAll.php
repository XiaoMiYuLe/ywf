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
 * 
 * 获得所有有效的优惠券
 * @author zhangr01
 * 
 */
class Api_Coupon_FetchAll
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run ($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                $offset = ($res['data']['page'] - 1) * $res['data']['pageSize'];
                $order = 'ctime DESC';
                $now = date(('Y-m-d H:i:s'), time());
                $where = "disabled = 0 AND status = 1 AND grant_stime <= '{$now}' AND grant_etime >= '{$now}' AND is_del = 0";
                if ($res['data']['coupon_type']) {
                    $where .= ' AND coupon_type = '.$res['data']['coupon_type'];
                }
                $coupon = Coupon_Model_Category::instance()->fetchCoupons($where, $order, $res['data']['pageSize'], $offset);
                $res['data']['coupon'] = $coupon ? $coupon : array();
                $res['data']['count'] = Coupon_Model_Category::instance()->countCouponList($where);
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '优惠券列表获取失败。错误信息：' . $e->getMessage();
            }
        }
        return $res;
    }
    
    /**
     * 验证参数
     * @param unknown $params
     * @return multitype:number string
     */
    private static function validate ($params)
    {
        /* 校验参数 */
        try {
            if (! $params['page'] || ! is_numeric($params['page'])) {
                $params['page'] = 1;
            }
            if (! $params['pageSize'] || ! is_numeric($params['pageSize'])) {
                $params['pageSize'] = 15;
            }
            if ($params['coupon_type'] && ! is_numeric($params['coupon_type'])){
                throw new Zeed_Exception('优惠券类型参数格式错误');
            }
        } catch (Zeed_Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '验证失败。错误信息：' . $e->getMessage();
        }

        self::$_res['data'] = $params;
        return self::$_res;
    }

}

// End ^ Native EOL ^ encoding
