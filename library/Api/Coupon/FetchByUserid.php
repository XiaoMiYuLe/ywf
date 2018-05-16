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
 * @author     Cyrano
 * @since      2015-09-06
 * @version    SVN: $Id$
 */

/**
 * 用户中心展示所有优惠券列表
 * @author zhangr01
 */
class Api_Coupon_FetchByUserid
{
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] === 0) {
            try {
                /* 检查用户是否存在 */
                if (! $userid = Cas_Token::getUserIdByToken($res['data']['token'])) {
                    throw new Zeed_Exception('查无此TOKEN数据');
                }
                if (! Cas_Model_User::instance()->fetchByPK($userid)) {
                    throw new Zeed_Exception('查无此用户信息');
                }
                
                $offset = ($res['data']['page'] - 1) * $res['data']['pageSize'];
                $order = 'coupon_listing.ctime DESC';
                $now = date(('Y-m-d H:i:s'), time());
                $where = 'userid = ' . $userid;
                
                if ($res['data']['useable'] == 1){
                    $where .= " AND coupon_listing.disabled = 0 AND coupon_listing.is_del = 0 AND cpns_status = 0 AND grant_stime <= '{$now}' AND grant_etime >= '{$now}'";
                    $where .= " AND coupon_category.disabled = 0 AND coupon_category.status = 1 AND coupon_category.is_del = 0";
                } else if ($res['data']['useable'] == -1){
                    $where .= " AND ( coupon_listing.disabled != 0 || coupon_listing.is_del != 0 || cpns_status != 0 || grant_stime > '{$now}' || grant_etime < '{$now}')";
                    $where .= " AND ( coupon_category.disabled != 0 || coupon_category.status != 1 || coupon_category.is_del != 0 )";
                }
                if ($res['data']['coupon_type']){
                    $where .= ' AND coupon_type = ' . $params['coupon_type'];
                }
                if ($res['data']['orderby'] == 1){
                    $order = 'valid_etime ASC';
                } else if ($res['data']['orderby'] == 2){
                    $order = 'face_value DESC';
                }
                
                $userCoupon = Coupon_Model_Listing::instance()->fetchUserListingByWhere($where, $order, $res['data']['pageSize'], $offset);
                $count = Coupon_Model_Listing::instance()->CountUserListingByWhere($where);

                /* 返回数据 */
                $res['data']['userCoupon'] = $userCoupon ? $userCoupon : array();
                $res['data']['count'] = $count;
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = "获取用优惠券信息错误：" . $e->getMessage();
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
            if (! $params['page'] || ! is_numeric($params['page'])) {
                $params['page'] = 1;
            }
            if (! $params['pageSize'] || ! is_numeric($params['pageSize'])) {
                $params['pageSize'] = 15;
            }
            // 1为通用， 2为满减
            if ($params['coupon_type'] && ! is_numeric($params['coupon_type'])){
                throw new Zeed_Exception('参数优惠券类型错误');
            }
            // 1为可用，-1为不可用
            if ($params['useable'] && ! is_numeric($params['useable'])){
                throw new Zeed_Exception('参数是否可用类型错误');
            }
            if ( ! $params['useable']){
                $params['useable'] = 1;
            }
            // 1为到期时间排序，2为面额排序
            if ($params['orderby'] && ! is_numeric($params['orderby'])){
                throw new Zeed_Exception('排序参数错误');
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