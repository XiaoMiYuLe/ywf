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
 * 发布转让
 */
class Api_Two_PublishTransfer
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        	    /* 检查用户是否存在 */
        	    $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['userid']}'");
        	    if (!$userExists) {
        	        throw new Zeed_Exception('该用户不存在，请重新输入');
        	    }
        	     
        	    /* 检查用户状态 */
        	    if($userExists[0]['status'] == 1 ){
        	        throw new Zeed_Exception('该账号已禁用，请重新输入');
        	    }
        	    
        	    $order = Bts_Model_Order::instance()->fetchByWhere("order_id = {$res['data']['order_id']} and order_status=2 and transfer_status=0");

        	    if(empty($order)){
        	        throw new Zeed_Exception('订单不存在');
        	    }
        	    if(!empty($order[0]['goods_id'])){
        	       $goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$order[0]['goods_id']} and is_del=0");
        	        if(empty($goods)){
        	            throw new Zeed_Exception('该产品已被删除');
        	        }
        	    }
        	    
        	    //是否支持转让
        	    if($order[0]['transfer_mindate']&&$order[0]['transfer_maxdate']){
        	        $now = strtotime(date("Y-m-d"));
        	        $transfer_mindate = strtotime($order[0]['transfer_mindate']);
        	        $transfer_maxdate = strtotime($order[0]['transfer_maxdate']);
        	        if($transfer_mindate>$now || $now>$transfer_maxdate){
        	            throw new Zeed_Exception('该订单不在转让时间内');
        	        }
        	    }else{
        	         throw new Zeed_Exception('该订单不支持转让');
        	    }
                
        	    //持有天数
        	    $startdate = strtotime(date("Y-m-d H:i:s"));
        	    $enddate = strtotime($order[0]['ctime']);
        	    $days = round(($startdate-$enddate)/3600/24);
        	   if($days<$goods[0]['distance_order']){
        	       throw new Zeed_Exception('持有天数不少于'.$goods[0]['distance_order'].'天');
        	   }
        	   
        	   //截止到期天数
        	   $date1 = strtotime($order[0]['ctime']);
        	   $date2 = strtotime($order[0]['cash_time']);
        	   $days_cash = round(($date2-$date1)/3600/24);
        	   
        	   if($days_cash<$goods[0]['distance_cash']){
        	       throw new Zeed_Exception('截止到期天数不少于'.$goods[0]['distance_cash'].'天');
        	   }
        	   
        	   //转让价格
                if (strpos($res['data']['transfer_price'], '.')) {
                    throw new Zeed_Exception('转让价格请输入整数');
                } 
        	   
        	    if($res['data']['transfer_price']<$order[0]['buy_money']*(1+$goods[0]['rate_min'])){
        	       throw new Zeed_Exception('转让价格不小于最低转让价格');
        	    }
        	    
        	    if($res['data']['transfer_price']>$order[0]['buy_money']*(1+$goods[0]['rate_max'])){
        	        throw new Zeed_Exception('转让价格不大于最高转让价格');
        	    }
        	    
        	    $update = Bts_Model_Order::instance()->update(array('transfer_status'=>1,'transfer_price'=>$res['data']['transfer_price'],'counter_money'=>$res['data']['counter_money'],'mtime'=>date("Y-m-d H:i:s"),),"order_id = {$res['data']['order_id']}");
        	    
        	    if(!$update){
        	        throw new Zeed_Exception('发布转让失败');
        	    }
        	    
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '发布转让失败。错误信息：' . $e->getMessage();
        		return $res;
        	}
        }
        return $res;
    }
    
    /**
     * 验证参数
     */
    public static function validate($params)
    {
        if (! isset($params['userid']) || strlen($params['userid']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '用户id 未提供';
            return self::$_res;
        }

        if (! isset($params['order_id']) || strlen($params['order_id']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '订单id 未提供';
            return self::$_res;
        }
        
        if (! isset($params['transfer_price']) || strlen($params['transfer_price']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '转让价格未提供';
            return self::$_res;
        }

        if (! isset($params['counter_money']) || strlen($params['counter_money']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '手续费未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
