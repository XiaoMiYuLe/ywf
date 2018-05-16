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
 * 获取产品投资记录
 */
class Api_Goods_BuyRecordReal
{

    /**
     * 返回参数
     */
    protected static $_res = array(
            'status' => 0,
            'error' => '',
            'data' => ''
    );
    /*默认条数*/
    protected static $_perpage=20;
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
            	/*检验产品是否有效*/
                if (!$goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$res['data']['goods_id']} and is_del = 0")){
                	throw new Zeed_Exception('该产品不存在或已被删除');
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
                
                /*获取该产品相关订单*/
               	$content = Bts_Model_Order::instance()->fetchByWhere("goods_id = {$res['data']['goods_id']} and is_pay = 1","ctime desc",$res['data']['psize'],$offset,array('phone','buy_money','ctime'));
               	
               	//总记录数
               	$count = Bts_Model_Order::instance()->getCount("goods_id = {$res['data']['goods_id']} and is_pay = 1");
               	
               	//计算总页数
               	$pageCount = ceil($count / $res['data']['psize']);
               	
               	$list['content'] = (array)$content;
               	$list['totalnum'] = $count;//总记录数
               	$list['currentpage'] = $res['data']['p'];//当前页
               	$list['totalpage'] = $pageCount;//总页数
               	
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取产品投资记录出错。错误信息：' . $e->getMessage();
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
    	if (!isset($params['goods_id']) || strlen($params['goods_id'])<1) {
    		self::$_res['status'] = 1;
    		self::$_res['error'] = '参数产品ID goods_id 未提供';
    		return self::$_res;
    	}
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
