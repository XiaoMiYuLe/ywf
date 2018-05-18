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
 * 撤销转让
 */
class Api_Two_RevokeTransfer
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
        	    
        	    $order = Bts_Model_Order::instance()->fetchByWhere("order_id = {$res['data']['order_id']}");

        	    if($order[0]['transfer_status']==2 || $order[0]['transfer_status']==3){
        	        throw new Zeed_Exception('该订单在转让中或已转让');
        	    }elseif($order[0]['transfer_status']==1){
        	        $update = Bts_Model_Order::instance()->update(array('transfer_status'=>0,'transfer_price'=>null,'counter_money'=>null,'mtime'=>date("Y-m-d H:i:s"),),"order_id = {$res['data']['order_id']}");
        	    }
        	    
        	    if(!$update){
        	        throw new Zeed_Exception('撤销转让失败');
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
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
