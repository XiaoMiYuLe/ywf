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
 * 获取转让列表
 * 读取表 goods_content
 */
class Api_Two_GetTransferList
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
            	
            	$where = "(transfer_status =1 or transfer_status =2) and is_del=0 and (order_status=2 or order_status=3)";
            	
            	$order = "ctime desc";
            	
            	$order = Bts_Model_Order::instance()->fetchByWhere($where,$order,$res['data']['psize'],$offset);
            	//$a = Cas_Model_User_Voucher::instance()->getAdapter()->getProfiler()->getLastQueryProfile()->getQuery();
            	$count = Bts_Model_Order::instance()->getCount($where);
            	// 计算总页数
            	$pageCount = ceil($count / $res['data']['psize']);
            	
            	//数据处理
            	if(!empty($order)){
            	    foreach ($order as $k=>&$v){
            	        //转让价
            	        $v['transfer_price_show'] = number_format($v['transfer_price']);
            	        //差价
            	        $v['price_difference'] =  '￥'.($v['buy_money'] - $v['transfer_price']);
            	        //查产品
            	        $goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$v['goods_id']}");

            	        //展示的预期年化收益率
            	        $v['yield_show'] = round(($goods[0]['yield']*$v['buy_money'])/$v['transfer_price'],1);
            	        $v['yield'] = $goods[0]['yield'];
            	        
            	        //剩余天数:当前时间距离转让区间上限 transfer_maxdate 的天数
            	        $time = strtotime(date("Y-m-d"));
            	        $transfer_maxdate  = strtotime($v['transfer_maxdate']);
            	        $v['distance_days'] = round(($transfer_maxdate-$time)/3600/24)+1;
            	        //备注
            	        $v['comment'] = $goods[0]['comment'];
            	        $v['buy_money'] = (int)$v['buy_money'];
            	    }
            	}
            	
            	/**
            	 * 数据数组 总条数 当前页码 总页数 具体产品信息
            	 */
            	$list = array(
            			'totalnum' => $count,
            			'currentpage' => (int)$res['data']['p'],
            			'totalpage' => $pageCount,
            			'info' => (array)$order,
            	);
                    
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取转让列表出错。错误信息：' . $e->getMessage();
                return self::$_res;
            }
            $res['data'] = $list;
        }
        return $res;
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
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
