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
 * 获取订单列表
 * 读取表bts_order
 */
class Api_Order_GetOrderList
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
     *
     * @var int 默认条数 接口可以不传递该参数
     */
    protected static $_perpage = 15;

    /**
     * 运行方法
     *
     * @param string $params            
     * @throws Zeed_Exception
     * @return multitype:number string
     */
    public static function run ($params = null)
    {
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            try {
            	/*校验用户是否有效*/
            	if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
            		throw new Zeed_Exception('该用户不存在或已被冻结');
            	}
            	
            	// 分页
            	if(empty($res['data']['p'])) {
            		$res['data']['p'] = 1;
            	}
            	
            	/*默认每页显示数*/
            	if(empty($res['data']['psize'])) {
            		$res['data']['psize'] = self::$_perpage;
            	}
            	
            	/*where条件*/
            	$where = "is_del = 0 and userid = {$res['data']['userid']}";
            	if ($res['data']['tag'] == 1) {
                    $where .= " and (order_status = 1)";
            	}else if ($res['data']['tag'] == 2) {
                    $where .= " and (order_status = 2)";
                }else if ($res['data']['tag'] == 3) {
                    $where .= " and (order_status = 3)";
                }elseif ($res['data']['tag'] == 4) {
                    $where .= " and order_status = 4";
            	}
            		
            	$order = "ctime desc";
            	
            	/*总记录数*/
            	$count = Bts_Model_Order::instance()->getCount($where);
            	
            	// 计算总页数
            	$pageCount = ceil($count / $res['data']['psize']);
            	
//             	if ($pageCount > 0 && $res['data']['p'] > $pageCount) {
//             		$res['data']['p'] = $pageCount;
//             	}
            	
            	$page = $res['data']['p'] - 1;
            	$offset = $page * $res['data']['psize'];
            	
            	/*获取订单列表内容*/
            	$content = Bts_Model_Order::instance()->fetchByWhere($where,$order,$res['data']['psize'],$offset);
            	if ($content) {
            		foreach ($content as $k => &$v) {
            		    //手续费
            		    $goods = Goods_Model_List::instance()->fetchByWhere("goods_id = '{$v['goods_id']}' and is_del=0");
            		    if($goods){
            		        $v['counter_money'] = $v['buy_money']*$goods[0]['counter_fee'];
            		        $v['rate_max_money'] = $v['buy_money']*(1+$goods[0]['rate_max']);
            		        $v['rate_min_money'] = $v['buy_money']*(1+$goods[0]['rate_min']);
            		    }else{
            		        $v['counter_money '] =0;
            		        $v['rate_max_money'] = $v['buy_money'];
            		        $v['rate_min_money'] = $v['buy_money'];
            		    }
            		    
            		    //是否支持转让
            		    if($v['transfer_mindate']&&$v['transfer_maxdate']){
            		        $now = strtotime(date("Y-m-d"));
            		        $transfer_mindate = strtotime($v['transfer_mindate']);
            		        $transfer_maxdate = strtotime($v['transfer_maxdate']);
            		        if($transfer_mindate<=$now && $now<=$transfer_maxdate){
            		            $v['is_transfer'] = 1;//是
            		            
            		            //转让状态展示
            		            if($v['transfer_status']==0){
            		                $v['transfer_name'] = '转让';
            		            }else{
            		                $v['transfer_name'] = '撤销';
            		            }
            		        }else{
            		            $v['is_transfer'] = 2;//否
            		        }
            		    }else{
            		        $v['is_transfer'] = 2;//否
            		        $v['transfer_name'] = '';
            		        $v['transfer_reset'] = '';
            		    }
            		    
            		    if($v['goods_pattern']==1 || $v['goods_pattern']==3 || $v['goods_pattern']==4){
            		        $v['is_transfer'] = 2;//否
            		    }
            		    
            		    
            			$v['buy_money'] = number_format($v['buy_money'],2);
            			$v['real_money'] = number_format($v['real_money'],2);
            			$v['bts_yield'] = number_format($v['bts_yield'],2);
            		}
            	}
            	
            	/**
            	 * 数据数组 总条数 当前页码 总页数 具体订单信息
            	 */
            	$list = array(
            			'totalnum' => $count,
            			'currentpage' => $res['data']['p'],
            			'totalpage' => $pageCount,
            			'info' => (array)$content,
            	);
                    
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取订单列表出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            self::$_res['data'] = $list;
        }
        return self::$_res;
    }

    /**
     *
     *
     * 验证方法
     * 
     * @param array $params            
     * @return multitype:number string
     */
    public static function validate ($params)
    {
    	/*校验参数*/
    	if (! isset($params['userid']) || strlen($params['userid']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数用户ID userid 未提供';
            return self::$_res;
        }
        if (! isset($params['tag']) || strlen($params['tag']) < 1) {
        	self::$_res['status'] = 1;
        	self::$_res['error'] = '参数标识 tag 未提供';
        	return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
