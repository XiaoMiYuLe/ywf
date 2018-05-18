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
class Api_Bts_Order_FastCreate
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
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            /* 生成订单 */
            $res = Bts_Model_Order::instance()->transaction($res['data']['content_id'], $res['data']['userid'], $res['data']['buy_num'], $res['data']['consignee'], $res['data']['order_type']);
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
        /**
         * 校验参数
         */
        if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 token未提供或无效的token';
            return self::$_res;
        }
        $params['userid'] = Cas_Token::getUserIdByToken($params['token']);
        
        if (! $params['content_id']) {
            
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数商品ID content_id 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
