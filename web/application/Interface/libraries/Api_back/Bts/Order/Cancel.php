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
 * 订单取消
 * 
 * @author Administrator
 *        
 */
class Api_Bts_Order_Cancel
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
            
            try {
                
                /* 检查用户是否存在 */
                if (! $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid'])) {
                    throw new Zeed_Exception('该用户不存在');
                }
                $userExists = current($userExists);
                
                /* 检查订单与用户的归属关系 */
                if (!$order = Bts_Model_Order::instance()->getOderByNumber($res['data']['order_number'])){
                    throw new Zeed_Exception('该订单不存在');
                }
                if ($order['userid'] != $res['data']['userid']) {
                    throw new Zeed_Exception('此订单并不属于该用户，请勿非法操作');
                }
                
                /* 检查订单当前状态 */
                if ($order['is_cancel'] != 0) {
                    throw new Zeed_Exception('订单已取消');
                }
                
                /* 检查订单当前状态 */
                if ($order['status'] != 2) {
                    throw new Zeed_Exception('订单状态无法取消');
                }
                
                /* 标记订单信息is_cancel为1 */
                $set = array(
                        'is_cancel' => 1
                );
                
		       	// 更新订单信息
		     	Bts_Model_Order::instance()->update($set, "order_number='{$order['order_number']}'");
		        
		        /* 记录日志 */
		        $set_log = array(
                        'order_number' => $order['child_order_number'],
                        'order_type' => '1',
		        		'type' => 1,
                        'admin_userid' => $userExists['userid'],
                        'content' => "userid 为{$userExists['userid']}的用户取消了订单号为{$res['data']['order_number']}的订单",
                        'ip' => Zeed_Util::clientIP(),
                        'ctime' => date(DATETIME_FORMAT)
                );
		        
		        Bts_Model_Order_Log::instance()->addForEntity($set_log);
		        
                /* 获取订单商品详情 */
                $items = Bts_Model_Order_Items::instance()->fetchByWhere('order_id = ' . $order['order_id']);
                
                /* 增加取消订单商品的库存 */
                foreach ($items as $kk => $vv) {
                    $goods = Goods_Model_Content::instance()->fetchByPK($items[$kk]['content_id']);
                    $log = array(
                            'stock' => $goods[0]['stock'] + $items[$kk]['buy_num']
                    );
                    Goods_Model_Content::instance()->updateForEntity($log, $items[$kk]['content_id']);
                }
                
                /* 返还用户积分 */
                
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '删除订单失败。错误信息：' . $e->getMessage();
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
         * 校验参数
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