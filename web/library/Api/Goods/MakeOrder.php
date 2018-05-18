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
 * 下单
 */
class Api_Order_MakeOrder
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
            	/*检验用户是否有效*/
            	if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
            		throw new Zeed_Exception("该用户不存在或已被冻结");
            	}
            	
            	/*检验产品是否有效*/
            	if (!$goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$res['data']['goods_id']} and is_del = 0")) {
            		throw new Zeed_Exception('该产品不存在');
            	}
               	if ($goods[0]['goods_status'] == 2) {
               		throw new Zeed_Exception('该产品已售罄');
               	} elseif ($goods[0]['goods_status'] == 3) {
               		throw new Zeed_Exception('该产品已下架');
               	}
               	if ($res['data']['buy_money'] < $goods[0]['low_pay']) {
               	    throw new Zeed_Exception('请确认投资金额大于当前产品的最低投资额');
               	}
               	if ($goods[0]['spare_fee'] < $res['data']['buy_money']) {
               		throw new Zeed_Exception('当前产品的剩余额度小于您的买入金额');
               	}
               	
               	if ($res['data']['buy_money'] > $goods[0]['high_pay']) {
               		throw new Zeed_Exception('请确认投资金额小于当前产品的最高投资额');
               	}
               	if ($goods[0]['increasing_pay'] && $goods[0]['increasing_pay'] != 0) {
               	    $q = ($res['data']['buy_money']-(int)$goods[0]['low_pay'])/(int)$goods[0]['increasing_pay'];
               		if (!is_int($q)) { 
               			throw new Zeed_Exception("请按递增".(int)$goods[0]['increasing_pay']."元填写投资金额");
               		}
               	}
               	if ($goods[0]['goods_type'] != $res['data']['goods_type']) {
               		throw new Zeed_Exception('该产品类型当前有变动,请与客服确认后再购买');
               	}
               	if ($goods[0]['goods_pattern'] != $res['data']['goods_pattern']) {
               		throw new Zeed_Exception('该产品有变动，状态异常，为了您的利益安全，如需购买，请与客服确认');
               	}
               	
               	/*获取订单的相关参数*/
               	$res['data']['is_del'] = 1;
               	$res['data']['ctime'] = date(DATETIME_FORMAT);
               	$now = date("Y-m-d",time());
               	echo 11;die;
               	/*如果传入了优惠券，则获取优惠券信息*/
               	if ($res['data']['voucher']) {
               		$voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("id = {$res['data']['voucher']}");
               		if ($voucher[0]['voucher_status'] == 2) {
               			throw new Zeed_Exception("该代金券已被使用，请重新确认订单");
               		}
               		if ($now > $voucher[0]['valid_data']) {
               			throw new Zeed_Exception('该代金券已失效，请重新核实');
               		}
               		
               		$res['data']['is_voucher'] = 1;
               		if($voucher[0]['type']==1){
               		    $res['data']['real_money'] = $res['data']['buy_money'] - $voucher[0]['voucher_money'];
               		}
               		
               		//加息处理
               		if($voucher[0]['type']==3){
               		    if($res['data']['yield']){
               		        $res['data']['yield'] +=  $voucher[0]['increase_interest']*100;
               		    }else{
               		        throw new Zeed_Exception('参数年化收益率 yield 未提供');
               		    }
               		}
               		
               	} else {
               		unset($res['data']['voucher']);
               		$res['data']['real_money'] = $res['data']['buy_money'];
               	}
               	
               	var_dump($res['data']['real_money']);die;
               	
               	/*如果产品模式是新手，则按期限计算结息时间*/
               	if ($res['data']['goods_pattern'] == 1) {
               		if (!$res['data']['yield']) {
               			throw new Zeed_Exception('参数年化收益率 yield 未提供');
               		}
               		if (!$res['data']['financial_period']) {
               			throw new Zeed_Exception('参数理财期限 financial_period 未提供');
               		}
               		$y = explode('-', $now);
               		$fulltime = mktime(0,0,0,$y[1],$y[2]+$res['data']['financial_period']-1,$y[0]);
               		$atime = mktime(0,0,0,$y[1],$y[2]+$res['data']['financial_period'],$y[0]);
               		$res['data']['end_time'] = date('Y-m-d',$fulltime);
               		$res['data']['cash_time'] = date('Y-m-d', $atime);
               		$res['data']['deal_status'] = $goods[0]['deal_status'];
               		$res['data']['principal_status'] = $goods[0]['principal_status'];
               		$res['data']['start_time'] = $now;
               		$res['data']['bts_yield'] = round(($res['data']['yield']/365)*0.01*$res['data']['financial_period']*$res['data']['buy_money'],2); 
               	} elseif ($res['data']['goods_pattern'] == 2) {
               		/*产品模式为直购，获取订单需要的相关信息*/
               		if (!$res['data']['end_time']) {
               			throw new Zeed_Exception('参数结息日期 end_time 未提供');
               		}
               		if (!$res['data']['cash_time']) {
               			throw new Zeed_Exception('参数兑付日期 cash_time 未提供');
               		}
               		if (!$res['data']['goods_broratio']) {
               			throw new Zeed_Exception('参数佣金比例 goods_broratio 未提供');
               		}
               		if (!$res['data']['yield']) {
               			throw new Zeed_Exception('参数年化收益率 yield 未提供');
               		}
               		if (!$res['data']['is_now']) {
               			throw new Zeed_Exception('参数起息方式 is_now 未提供');
               		}
               		if ($goods[0]['is_now'] == 1) {
               			$res['data']['start_time'] = $now;
               		} elseif ($goods[0]['is_now'] == 2) {
               			if (!$res['data']['start_time']) {
               				throw new Zeed_Exception('参数起息日期 start_time 未提供');
               			}
               		} else {
               			throw new Zeed_Exception('起息方式参数错误或参数缺失');
               		}
               		$res['data']['cash_time'] = $goods[0]['deal_date'];
               		$res['data']['deal_status'] = $goods[0]['deal_status'];
               		$res['data']['principal_status'] = $goods[0]['principal_status'];
               		$a = strtotime($res['data']['start_time']);
               		$b = strtotime($res['data']['end_time']);
               		$days=round(($b-$a)/3600/24)+1;
               		$res['data']['bts_yield'] = round(($res['data']['yield']/365)*0.01*$days*$res['data']['buy_money'],2);
               		$res['data']['brokerage'] = round($res['data']['buy_money']*$res['data']['goods_broratio']*0.01,2);
               	} elseif ($res['data']['goods_pattern'] == 3) {
               		/*产品模式为预约，获取订单需要的相关信息*/
               		if (!$res['data']['goods_broratio']) {
               			throw new Zeed_Exception('参数佣金比例 goods_broratio 未提供');
               		}
               		if (!$res['data']['yield']) {
               			throw new Zeed_Exception('参数年化收益率 yield 未提供');
               		}
               		$res['data']['cash_time'] = $goods[0]['deal_date'];
               		$res['data']['deal_status'] = $goods[0]['deal_status'];
               		$res['data']['principal_status'] = $goods[0]['principal_status'];
               		$res['data']['brokerage'] = round($res['data']['buy_money']*$res['data']['goods_broratio']*0.01,2);
               		$res['data']['is_del'] = 0;
               		$res['data']['order_status'] = 1;
               	}
                /*用户相关数据*/
           	        $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$user[0]['bank_id']}' and is_use=1 and is_del=0");
           	        if(!empty($bank_info)){
           	            $res['data']['bank_name'] = $bank_info[0]['bank_name'];
           	            $res['data']['bank_no'] = $bank_info[0]['bank_no'];
           	        }
               	$res['data']['username'] =$user[0]['username'];
               	$res['data']['phone'] =$user[0]['phone'];
               	
               	/*生成订单号*/
               /* 	$res['data']['order_no'] = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
               	
               	$fetchOrder = Bts_Model_Order::instance()->fetchByWhere("order_no='{$res['data']['order_no']}'");
               	
               	if($fetchOrder){
               	    $res['data']['order_no'] = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
               	} */
               	$res['data']['order_no'] = self::orderCode();
               	/*添加一条订单记录*/
               	$result = Bts_Model_Order::instance()->addForEntity($res['data']);
               	if (!$result) {
               		throw new Zeed_Exception('添加订单失败');
               	} else {
               		$res['data']['order_id'] = $result;
               	}
               	
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = $e->getMessage();
                return self::$_res;
            }
        }
        return $res;
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
        if (! isset($params['goods_id']) || strlen($params['goods_id']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数产品ID goods_id 未提供';
            return self::$_res;
        }
        if (! isset($params['goods_pattern']) || strlen($params['goods_pattern']) < 1) {
        	self::$_res['status'] = 1;
        	self::$_res['error'] = '参数产品模式 goods_pattern 未提供';
        	return self::$_res;
        }
        if (! isset($params['goods_type']) || strlen($params['goods_type']) < 1) {
        	self::$_res['status'] = 1;
        	self::$_res['error'] = '参数产品类型 goods_type 未提供';
        	return self::$_res;
        }
        if (! isset($params['goods_name']) || strlen($params['goods_name']) < 1) {
        	self::$_res['status'] = 1;
        	self::$_res['error'] = '参数产品名称 goods_name 未提供';
        	return self::$_res;
        }
        if (! isset($params['buy_money']) || strlen($params['buy_money']) < 1) {
        	self::$_res['status'] = 1;
        	self::$_res['error'] = '参数投资金额 buy_money 未提供';
        	return self::$_res;
        }
        if (! isset($params['userid']) || strlen($params['userid']) < 1) {
        	self::$_res['status'] = 1;
        	self::$_res['error'] = '参数用户ID userid 未提供';
        	return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
    
    /*推荐码不重复*/
    public  static function orderCode(){
        $result = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
         
        while((self::returnCode($result))==false)
        {
            $result = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        };
        return $result;
    }
     
    public static function returnCode($result){
        $id = Bts_Model_Order::instance()->fetchByWhere(" order_no='{$result}'");
        if($id){
            return false;
        }
        return true;
    }
}

// End ^ Native EOL ^ encoding
