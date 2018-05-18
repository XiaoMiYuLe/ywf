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
 * 获取产品列表
 * 读取表 goods_content
 */
class Api_Goods_GetGoodsList
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
            	
            	/*where条件*/
            	$where = "(goods_status = 1 or goods_status = 2) and is_del = 0 and is_manager = 0";
            	//产品区
            	if($res['data']['tag']==1){
            	    $where .= " and (goods_pattern=1 or goods_pattern=2 or goods_pattern=4)";
            	}elseif($res['data']['tag']==2){
            	//预约区
            	   $where .=" and goods_pattern=3";
            	}
            	
            	if ($res['data']['cat']) {
            		$where .= " and goods_type = {$res['data']['cat']}";
            	}
            	/*如果当前有用户登录，则判断该用户是否是新手*/
            	if ($res['data']['userid']) {
            		if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
            			throw new Zeed_Exception('该用户不存在或已被冻结');
            		} else {
            			if ($user[0]['is_buy'] == 1) {
            			    //用户体验金
            			    $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$res['data']['userid']} and type=2 and voucher_status=1 and is_manager=0");
            			    if(!empty($voucher)){
            			        $where .= " and (goods_pattern = 2 or goods_pattern = 3 or goods_pattern = 4)";
            			    }else{
            			        $where .= " and (goods_pattern = 2 or goods_pattern = 3)";
            			    }
            			}else{
            			    //用户体验金
            			    $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$res['data']['userid']} and type=2 and voucher_status=1 and is_manager=0");
            			    if(!empty($voucher)){
            			        $where .= " and (goods_pattern =1 or goods_pattern = 2 or goods_pattern = 3 or goods_pattern = 4)";
            			    }else{
            			        $where .= " and (goods_pattern =1 or goods_pattern = 2 or goods_pattern = 3)";
            			    }
            			}
            		}
            	}
            	
            	$order[] = "sort desc";
            	
            	$order[] = "goods_status asc";
            	
            	/*如果排序条件有传递值，则对其进行相应值排序*/
            	if ($res['data']['order'] == 1) {
            		$order[] = "yield desc";
            	} elseif ($res['data']['order'] == 2) {
            		$order[] = "financial_period desc";
            	}
            	$order[] = "ctime desc";
            	//$order[] = "yield asc";
            	
            	/*总记录数*/
            	$count = Goods_Model_List::instance()->getCount($where);
            	
            	// 计算总页数
            	$pageCount = ceil($count / $res['data']['psize']);
            	
//             	if ($pageCount > 0 && $res['data']['p'] > $pageCount) {
//             		$res['data']['p'] = $pageCount;
//             	}
            	
            	$page = $res['data']['p'] - 1;
            	$offset = $page * $res['data']['psize'];
            	
            	$filed = array('goods_id','goods_name','yield','financial_period','low_pay','comment','goods_type','goods_pattern','goods_status','all_fee','spare_fee','increasing_pay','is_voucher','is_interest','buy_num');
            	
            	/*获取产品列表内容*/
            	$content = Goods_Model_List::instance()->fetchByWhere($where,$order,$res['data']['psize'],$offset,$filed);
            	if ($content) {
            		foreach ($content as $k => &$v) {
            		    $v['low_pay'] = (string)(int)$v['low_pay'];
            			if ($v['all_fee'] > 0) {
            				$v['schedule'] = (($v['all_fee']-$v['spare_fee'])/$v['all_fee'])*100;
            			}
            			if ($v['schedule'] < 1 && $v['schedule'] > 0) {
            				$v['schedule'] = 1;
            			} else {
            				$v['schedule'] = floor($v['schedule']);
            			}
            			$v['all_fee_view'] = number_format($v['all_fee'],2);
            			$v['spare_fee_view'] = number_format($v['spare_fee'],2);
            			
            			//是否支持代金券  1:支持 2：不支持
            			if($v['is_voucher']==1){
            			    $v['voucher_name'] = '代金券';
            			}else{
            			    $v['voucher_name'] = '';
            			}
            			
            			//是否支持加息券  1:支持 2：不支持
            			if($v['is_interest']==1){
            			    $v['interest_name'] = '加息券';
            			}else{
            			    $v['interest_name'] = '';
            			}
            			
            		}
            	}
            	/**
            	 * 数据数组 总条数 当前页码 总页数 具体订单信息
            	 */
            	$list = array(
            			'totalnum' => $count,
            			'currentpage' => (int)$res['data']['p'],
            			'totalpage' => $pageCount,
            			'info' => (array)$content,
            	);
                    
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取商品列表出错。错误信息：' . $e->getMessage();
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
