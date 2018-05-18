<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   BTS
 * @package    cart
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2014-08-08
 * @version    SVN: $Id$
 */

/**
 * 购物车添加商品
 */
class Api_Bts_Cart_Add
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
    	// 验证方法
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
                // 更新购物车库
                $res = Bts_Helper_ShopCart::addToCart($res['data']['content_id'], $userid, $res['data']['quantity']);
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
        /* 校验参数  */
        try {
            if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])) {
                throw new Zeed_Exception('无效的token');
            }
            if (! $params['content_id']) {
                throw new Zeed_Exception('参数订单类型 content_id 未提供');
            }
        } catch (Zeed_Exception $e) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '信息错误：' . $e->getMessage();
        }
        
        // 添加默认为1
        $params['quantity'] = $params['quantity'] ? $params['quantity'] : 1;
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
