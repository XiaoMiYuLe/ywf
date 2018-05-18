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
 * 使用
 */
class Api_Two_UseExperienceByUser
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
            	if (!$goods = Goods_Model_List::instance()->fetchByWhere("goods_id = {$res['data']['goods_id']} and goods_pattern = 4 and is_del = 0")) {
            		throw new Zeed_Exception('该体验金产品不存在');
            	}
                
            	if(!$voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$res['data']['userid']} and type=2 and voucher_status=1 and is_manager=0")){
            	    throw new Zeed_Exception('该体验金不存在或已使用');
            	}
            	
            	/*绑定银行卡信息*/
            	$bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$user[0]['bank_id']}' and is_use=1 and is_del=0");
            	 
            	if(empty($bank_info)){
            	    throw new Zeed_Exception('请绑定银行卡');
            	}
            	
            	/*获取订单的相关参数*/
            	$res['data']['is_del'] = 0;
            	$res['data']['is_pay'] = 1;
            	$res['data']['ctime'] = date(DATETIME_FORMAT);
            	$res['data']['order_status'] = 2;
            	$now = date("Y-m-d",time());
            	
            	/*获取体验金信息*/
            	if ($user[0]['userid']) {
            	    $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$user[0]['userid']} and type=2 and is_manager=0");
            	    if ($voucher[0]['voucher_status'] == 2) {
            	        throw new Zeed_Exception("体验金已使用");
            	    }
            	    if ($now > $voucher[0]['valid_data']) {
            	        throw new Zeed_Exception('体验金已失效');
            	    }
            	    $res['data']['real_money'] = 0;
            	    $res['data']['buy_money'] = $voucher[0]['voucher_money'];
            	     
            	}
            	
            	if (!$goods[0]['yield']) {
            	    throw new Zeed_Exception('参数年化收益率 yield 未提供');
            	}
            	if (!$goods[0]['financial_period']) {
            	    throw new Zeed_Exception('参数理财期限 financial_period 未提供');
            	}
            	
            	$y = explode('-', $now);
            	$fulltime = mktime(0,0,0,$y[1],$y[2]+$goods[0]['financial_period']-1,$y[0]);
            	$atime = mktime(0,0,0,$y[1],$y[2]+$goods[0]['financial_period'],$y[0]);
            	$res['data']['end_time'] = date('Y-m-d',$fulltime);
            	$res['data']['cash_time'] = date('Y-m-d', $atime);
            	$res['data']['deal_status'] = 1;
            	$res['data']['principal_status'] = 2;
            	$res['data']['start_time'] = $now;
            	$res['data']['bts_yield'] = round(($goods[0]['yield']/365)*0.01*$goods[0]['financial_period']*$res['data']['buy_money'],2);
            	
            	
               	/*获取订单的相关参数*/
            	$res['data']['goods_id'] = $goods[0]['goods_id'];
            	$res['data']['goods_type'] = $goods[0]['goods_type'];
            	$res['data']['goods_pattern'] = 4;
            	$res['data']['yield'] = $goods[0]['yield'];
            	$res['data']['goods_name'] = $goods[0]['goods_name'];
            	
           		$res['data']['brokerage'] = 0;
           		
           		$res['data']['voucher'] = $voucher[0]['id'];
           		$res['data']['is_voucher'] = 1;
           		$res['data']['pay_time'] = date("Y-m-d H:i:s");
               
                /*用户相关数据*/
           	        $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$user[0]['bank_id']}' and is_use=1 and is_del=0");
           	        if(!empty($bank_info)){
           	            $res['data']['bank_name'] = $bank_info[0]['bank_name'];
           	            $res['data']['bank_no'] = $bank_info[0]['bank_no'];
           	        }
               	$res['data']['username'] =$user[0]['username'];
               	$res['data']['phone'] =$user[0]['phone'];
               	
               	//生成订单号
               	$res['data']['order_no'] = self::orderCode();
               	
               	
               	/*添加一条订单记录*/
               	$result = Bts_Model_Order::instance()->addForEntity($res['data']);
               	
               
               	//体验金使用
               	if(!empty($result)){
               	    $use_time = date(DATETIME_FORMAT);
               	    //暂时先不改成2
               	   $voucher_user = Cas_Model_User_Voucher::instance()->update(array('voucher_status'=>2,'use_time'=>"{$use_time}",'order_id'=>"{$result}"),"id={$voucher[0]['id']} and type=2 and is_manager=0");
               	    if(!empty($voucher_user)){
               	        //购买人数
               	        self::addOne($res['data']['goods_id']);
               	    }
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
            self::$_res['error'] = '产品id未提供';
            return self::$_res;
        }
        if (! isset($params['userid']) || strlen($params['userid']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '用户ID未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
    
    /*订单号不重复*/
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
    
    //购买人数加1
    public static function addOne($goods_id){
        $good = Goods_Model_List::instance()->fetchByWhere("goods_id='{$goods_id}'");
        $num = $good[0]['buy_num']+1;
        Goods_Model_List::instance()->update(array('buy_num'=>$num),"goods_id = '{$goods_id}'");
    }
}

// End ^ Native EOL ^ encoding
