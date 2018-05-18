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
class Api_Cas_ElectronicContract
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array('order_no');
	/* 根据用户手机号登录 */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] === 0) {
            try {
                $order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$res['data']['order_no']}' and order_status=2 or order_status=3");
                if(empty($order)){
                    throw new Zeed_Exception('该订单不存在');
                }
                
                $url = Zeed_Config::loadGroup('urlmapping');
                $login_url = $url['store_url_login'].'/cas/word/index?order_no='.$res['data']['order_no'];
                $res['data'][url] = $login_url;
                if(empty($res['data'])){
                    $res['data'] = array();
                }
            } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = "错误信息：" . $e->getMessage();
                return $res;
            }
        }
        
        return $res;
    }
    
    
    /**
     * 验证参数
     */
    public static function validate ($params)
    {
        if (! isset($params['order_no']) || ! $params['order_no']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '订单号未提供';
            return self::$_res;
        }
        /* 组织数据 */
        $set = array();
        foreach (self::$_allowFields as $f) {
            $set[$f] = isset($params[$f]) ? $params[$f] : null;
        }
        self::$_res['data'] = $set;
        
        return self::$_res;
    }
     /*
     * 加密算法
     */
    public static function encrypt($str='',$salt='')
    {
        return MD5(MD5($str).$salt);
    }
}