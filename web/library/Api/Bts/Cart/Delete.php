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
 * 删除购物车中商品
 */
class Api_Bts_Cart_Delete
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');

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
                /* 执行删除 */
                $res['data'] = Bts_Helper_ShopCart::dropFromCart($res['data']['cart_ids'], $userid);
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '生成购物车失败。错误信息：' . $e->getMessage();
                return $res;
            }
        }
        return $res;
    }

    
    /**
     * 验证方法
     * @param unknown $params
     * @return multitype:number string
     */
    private static function validate ($params)
    {
        try {
            if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])) {
                throw new Zeed_Exception('无效的token');
            }
            if (! $params['cart_ids']) {
                throw new Zeed_Exception('参数订单类型 cart_ids 未提供');
            }
            if ($params['cart_ids']) {
                if (! is_string($params['cart_ids'])) {
                    throw new Zeed_Exception('参数格式不正确');
                }
                if (strpos($params['cart_ids'], ',')) {
                    $shopcatrs = explode(',', $params['cart_ids']);
                    foreach ($shopcatrs as $value) {
                        if (! Bts_Model_Cart::instance()->fetchByPK($value)) {
                            throw new Zeed_Exception('您提交信息不存在，请检查');
                        }
                    }
                } else {
                    if (! Bts_Model_Cart::instance()->fetchByPK($params['cart_ids'])) {
                        throw new Zeed_Exception('您提交信息不存在，请检查');
                    }
                }
            }
        } catch (Zeed_Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '信息错误：' . $e->getMessage();
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
