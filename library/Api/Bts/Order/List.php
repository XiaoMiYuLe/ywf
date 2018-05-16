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
 * 订单列表
 */
class Api_Bts_Order_List
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
                $redis_set = Trend_Model_Setting::instance()->fetchByFV('name', 'is_open_redis','val');
                $redis_val = empty($redis_set) ? 0 : $redis_set[0]['val'];
                
                $cache_key = md5(json_encode($res['data']));
                $list      = $redis_val == 1 ? Trend_Model_Redis::instance()->get($cache_key) : false;
                if (!$list) {
                
                    /* 检查用户是否存在 */
                    $userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid']);
            		if (! $userExists) {
            		    throw new Zeed_Exception('该用户不存在');
            		}
            		
            		// 如果没有提供分页 默认15条
            		/* 分页属性设置  */
            		$res['data']['psize'] = $res['data']['psize'] ? $res['data']['psize'] : 15;
            		$perpage = $res['data']['psize'];
            		$res['data']['p'] = $res['data']['p'] ? $res['data']['p'] : 1;
            		 
            		$offset = $perpage * ($res['data']['p'] - 1);
            		
            		// 查询条件
            		$where = " userid={$res['data']['userid']} AND is_del = 0 AND is_del_user = 0";
            		
            		// -1为查询所有订单
            		if ($res['data']['type'] != -1) {
            		    $where .= " AND status = {$res['data']['type']}";
            		}
            		
            		$order = ' ctime DESC';
            		$contents = Bts_Model_Order::instance()->getOrderByWhere($where, $order, $perpage, $offset);
            		$count = Bts_Model_Order::instance()->getCount($where);
            		
            		// 计算总页数
            		$pageCount = ceil($count / $res['data']['psize']);
            		
            		if ($res['data']['p'] > $pageCount) {
            		    $res['data']['p'] = $pageCount;
            		}
            		
            	   /*
            		*  数据数组
            		*  总条数
            		*  当前页码
            		*  总页数
            		*  数据字典
            		*/
            		$list = array(
            		        'totalnum' => $count,
            		        'currentpage' => $res['data']['p'],
            		        'totalpage' => $pageCount,
            		        'info' => (array) $contents
            		);
            		
            		$list = json_encode($list);
            		if ($redis_val == 1){ Trend_Model_Redis::instance()->set($cache_key, $list);}
                }
                
                $res['data'] = json_decode($list,true);
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error']  = '生成订单列表错误。错误信息：' . $e->getMessage();
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
        ksort($params);
        /**
         *  校验参数
         */
        if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 token未提供或无效的token';
            return self::$_res;
        }
        $params['userid'] = Cas_Token::getUserIdByToken($params['token']);
               
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding