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
 * 获取用户收益记录
 */
class Api_Cas_GetEarnings
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
		        $where = "userid = {$res['data']['userid']} AND status = '+' and type !=1 and type !=6";
		        $order = 'record_id desc';
		        $record = Cas_Model_Record_Log::instance()->fetchByWhere($where , $order, $res['data']['psize'],$offset);
		        if(!empty($record)){
		            foreach($record as $k=>&$v){
		                if($v['type'] == 5 ){
		                    $v['type'] = '佣金结算';
		                    $v['goods_name'] = '佣金';
		                }elseif($v['type'] == 4){
		                    $v['type'] = '收益分配';
		                     /* 处理收益产品 名称  */
		                    if(!empty($v['order_id'])){
    		                    $goods_order = Bts_Model_Order::instance()->fetchByWhere("order_id = {$v['order_id']}");
        		                if(!empty($goods_order)){
        		                    $v['goods_name'] = $goods_order[0]['goods_name'];
        		                }
		                     }
		                }elseif($v['type'] == 0){
		                    $v['type'] = '红包奖励';
		                            
		                    /* 处理收益产品 名称  */
		                    if(!empty($v['order_id'])){
    		                    $goods_order = Bts_Model_Order::instance()->fetchByWhere("order_id = {$v['order_id']}");
        		                if(!empty($goods_order)){
        		                    $v['goods_name'] = $goods_order[0]['goods_name'];
        		                }
		                     }
		                }
		            }
		        }
		        //用户表读取累计收益
		        $total = $userExists[0]['earnings'];
		        
		        /*总记录数*/
		        $count = Cas_Model_Record_Log::instance()->getCount($where);
		         
		        // 计算总页数
		        $pageCount = ceil($count / $res['data']['psize']);
		        
		        $res['data']['total'] = (string)$total ? (string)$total : '0.00';//累计收益
		        $res['data']['totalnum'] = $count;//总记录数
		        $res['data']['currentpage'] = $res['data']['p'];//当前页
		        $res['data']['totalpage'] = $pageCount;//总页数
		        
		        $res['data']['content'] = $record ? $record : array();
		        unset($res['data']['p']);
		        unset($res['data']['psize']);
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取用户收益记录失败!错误信息：' . $e->getMessage();
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
        if (! isset($params['userid']) || ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 userid 未提供';
            return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
