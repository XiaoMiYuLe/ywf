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
 * 获取用户佣金
 */
class Api_Cas_GetUserBrokerage
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_perpage = 20;
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
		        if (! $userExists = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
		            throw new Zeed_Exception('该用户不存在或被冻结');
		        }
		        
		        // 分页
		        if(empty($res['data']['p'])) {
		            $res['data']['p'] = 1;
		        }
		         
		        /*默认每页显示数*/
		        if(empty($res['data']['psize'])) {
		            $res['data']['psize'] = self::$_perpage;
		        }
		        
		        
		        $page = $res['data']['p'] - 1;
		        $offset = $page * $res['data']['psize'];
		        
		        /* 查询条件 */
		        $where = "userid = {$res['data']['userid']}";
		        if($res['data']['brokerage_status'] == 1){
		            $where .= " AND brokerage_status=1";
		        }elseif($res['data']['brokerage_status'] == 2){
		            $where .= " AND brokerage_status=2";
		        }elseif($res['data']['brokerage_status'] == 3){
		            $where .= " AND brokerage_status=3";
		        }
		        $order = "order_time DESC";
		        $brokerage = Cas_Model_User_Brokerage::instance()->fetchByWhere($where , $order, $res['data']['psize'],$offset);
		        if(!empty($brokerage)){
    		        foreach ($brokerage as $k=>&$v){
    		            $order = Bts_Model_Order::instance()->fetchOrderById($v['order_id']);
    		            $v['goods_name'] = $order[0]['goods_name'];
    		        }
		        }
		        
		        /*总记录数*/
		        $count = Cas_Model_User_Brokerage::instance()->getCount($where);
		         
		        // 计算总页数
		        $pageCount = ceil($count / $res['data']['psize']);
		        
		        $res['data']['totalnum'] = $count;//总记录数
		        $res['data']['currentpage'] = $res['data']['p'];//当前页
		        $res['data']['totalpage'] = $pageCount;//总页数
		        
		        
		        $res['data']['content'] = $brokerage ? $brokerage : array();
		        unset($res['data']['p']);
		        unset($res['data']['psize']);
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取用户佣金失败。错误信息：' . $e->getMessage();
//         		$res['data'] = array();
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
        /* 校验必填项 */
        if (! isset($params['userid'])|| ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 userid 未提供';
            return self::$_res;
        }
        if (! isset($params['brokerage_status'])) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 brokerage_status 未提供';
            return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
