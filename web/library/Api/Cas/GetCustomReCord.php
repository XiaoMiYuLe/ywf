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
 * 获取客户投资记录
 */
class Api_Cas_GetCustomReCord
{

    /**
     * 返回参数
     */
    protected static $_res = array(
            'status' => 0,
            'error' => '',
            'data' => ''
    );
    protected static $_perpage = 20;
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
        // 执行参数验证
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            try {
            	/*检测用户是否有效*/
               	if (!Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
               		throw new Zeed_Exception('该用户不存在或已被冻结');
               	}
               	
               	/*查询当前用户的下线*/
            	$user = Cas_Model_User::instance()->fetchByWhere("parent_id = {$res['data']['userid']}");
            	

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
            	
            	/*拼接where条件*/
            	$arr = array();
            	if ($user) {
            		foreach ($user as $k => $v) {
            			$arr[] = $v['userid'];
            		}
            	}
            	$where = "is_del = 0 and is_pay = 1";
               	if (!empty($arr)) {
               		$arr = implode(',',$arr);
               		$where .= " and userid in (".$arr.")";
               	} else {
               		$where .= " and userid in (0)";
               	}
               	if ($res['data']['tag'] == 1) {
               		$where .= " and order_status = 2";
               	} elseif ($res['data']['tag'] == 2) {
               		$where .= " and (order_status = 3 or order_status = 4)";
               	} else {
               		throw new Zeed_Exception('参数 tag 值无效');
               	}
               	$order = "ctime desc";
               	
               	/*查询订单记录*/
               	$field = array('order_id','goods_name','userid','username','buy_money','end_time','ctime','order_status','cash_time');
               	$order = Bts_Model_Order::instance()->fetchByWhere($where,$order,$res['data']['psize'],$offset,$field);
               	if ($order) {
               		foreach ($order as $k => &$v) {
               			$v['buy_money'] = number_format($v['buy_money'],2);
               			$v['level'] = "一级";
               		}
               	}
               	
               	/*总记录数*/
               	$count = Bts_Model_Order::instance()->getCount($where);
               	 
               	// 计算总页数
               	$pageCount = ceil($count / $res['data']['psize']);
               	
               	$list['totalnum'] = $count;//总记录数
               	$list['currentpage'] = $res['data']['p'];//当前页
               	$list['totalpage'] = $pageCount;//总页数
               	$list['content'] = (array)$order;
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取客户投资记录出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            self::$_res['data'] = $list;
        }
        return self::$_res;
    }

    /**
     * 验证参数
     *
     * @param array $params            
     * @throws Zeed_Exception
     */
    public static function validate ($params)
    {
    	/*校验参数*/
    	if (!isset($params['userid']) || strlen($params['userid'])<1) {
    		self::$_res['status'] = 1;
    		self::$_res['error'] = '参数用户ID userid 未提供';
    		return self::$_res;
    	}
    	if (!isset($params['tag']) || strlen($params['tag'])<1) {
    		self::$_res['status'] = 1;
    		self::$_res['error'] = '参数交易标识 tag 未提供';
    		return self::$_res;
    	}
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
