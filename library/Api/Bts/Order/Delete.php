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
 * 删除订单
 */
class Api_Bts_Order_Delete
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');

    /**
     * 接口运行方法
     * 
     * @param string $params
     * @throws Zeed_Exception
     * @return string|Ambigous <string, multitype:number, multitype:number string , unknown, multitype:>
     */
    public static function run ($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
            	
                /* 检查用户是否存在 */
        		if (! $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
        		    throw new Zeed_Exception('该用户不存在');
        		}
        		$userExists = current($userExists);
        		
        		/* 更新订单状态 */
		        $set = array(
                        'is_del_user' => 1
                );
		        
		       	// 更新订单信息
		     	Bts_Model_Order::instance()->update($set, "order_number='{$res['data']['order_number']}' and userid = {$res['data']['userid']}");
		        		        
		        /* 记录日志 */
		        $set_log = array(
                        'order_number' => $res['data']['order_number'],
                        'order_type' => '1',
		        		'type' => 1,
                        'admin_userid' => $userExists['userid'],
                        'content' => "userid 为{$userExists['userid']}的用户删除了订单号为{$res['data']['order_number']}的订单",
                        'ip' => Zeed_Util::clientIP(),
                        'ctime' => date(DATETIME_FORMAT)
                );
		        
		        Bts_Model_Order_Log::instance()->addForEntity($set_log);
		        
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error']  = '删除订单失败。错误信息：' . $e->getMessage();
                return $res;
            }
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
         *  校验参数
         */
        if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 token未提供或无效的token';
            return self::$_res;
        }
        $params['userid'] = Cas_Token::getUserIdByToken($params['token']);
        
        if (! $params['order_number']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数订单编号 order_number 未提供';
            return self::$_res;
        }

        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
