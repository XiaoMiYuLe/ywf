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
class Api_Two_UseExperienceByManager
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
            	if (!$goods = Goods_Model_List::instance()->fetchByWhere("is_del = 0 and is_manager=1")) {
            		throw new Zeed_Exception('该产品不存在');
            	}
                
            	if(!$voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("id={$res['data']['voucher']}")){
            	    throw new Zeed_Exception('该推广金不存在');
            	}
            	
            	//推荐人必须为经纪人
            	if(!empty($user[0]['parent_id'])){
            	    $recommender = Cas_Model_User::instance()->fetchByWhere("userid = {$user[0]['parent_id']} and status=0");
            	    
            	    if(empty($recommender) || $recommender[0]['is_ecoman'] !=1){
            	        throw new Zeed_Exception('推荐人不是经纪人');
            	    }
            	    if($recommender[0]['is_market']==1){
            	        throw new Zeed_Exception('您在该推荐人下已使用该推广金');
            	    }
            	}else{
            	    throw new Zeed_Exception('请填写推荐人');
            	}
            	
            	
            	/*绑定银行卡信息*/
            	$bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$user[0]['bank_id']}' and is_use=1 and is_del=0");
            	 
            	if(empty($bank_info)){
            	    throw new Zeed_Exception('请绑定银行卡');
            	}
            	
            	/*获取订单的相关参数*/
            	$res['data']['is_del'] = 0;
            	$res['data']['order_status'] = 4;
            	$res['data']['is_pay'] = 1;
            	$res['data']['ctime'] = date(DATETIME_FORMAT);
            	$now = date("Y-m-d",time());
            	
            	/*如果传入了推广金，获取推广金信息*/
            	if ($res['data']['voucher']) {
            	    $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere("id = {$res['data']['voucher']} and is_manager=1");
            	    if ($voucher[0]['voucher_status'] == 2) {
            	        throw new Zeed_Exception("该推广金已使用");
            	    }
            	    if ($now > $voucher[0]['valid_data']) {
            	        throw new Zeed_Exception('该推广金已失效');
            	    }
            	    $res['data']['real_money'] = 0;
            	    $res['data']['buy_money'] = $voucher[0]['use_money'];
            	     
            	}
               	
            	
               	/*获取订单的相关参数*/
            	$res['data']['goods_id'] = $goods[0]['goods_id'];
            	$res['data']['goods_type'] = $goods[0]['goods_type'];
            	$res['data']['goods_pattern'] = $goods[0]['goods_pattern'];
            	$res['data']['yield'] = $goods[0]['yield'];
            	$res['data']['goods_name'] = $goods[0]['goods_name'];
            	
            	$res['data']['cash_time'] = $goods[0]['deal_date'];
           		$res['data']['deal_status'] = $goods[0]['deal_status'];
           		$res['data']['principal_status'] = $goods[0]['principal_status'];
           	    $res['data']['start_time'] = date(DATETIME_FORMAT);
           	    $res['data']['end_time'] = date(DATETIME_FORMAT);
           	    $res['data']['cash_time'] = date(DATETIME_FORMAT);
           		$res['data']['bts_yield'] = 0.00;
           		$res['data']['brokerage'] = round($res['data']['buy_money']*$goods[0]['goods_broratio']*0.01,2);
            
            	
               
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

               
               	//佣金处理,新手类产品不存在佣金
               	if($result){
               	    $voucher_money = $voucher[0]['voucher_money'] -$voucher[0]['use_money'];
               	    if($voucher_money<0){
               	        throw new Zeed_Exception("推广金不足一次使用");
               	    }elseif($voucher_money==0){
               	        //已使用
               	        $voucher_use = Cas_Model_User_Voucher::instance()->update(array('voucher_status'=>2),"id={$res['data']['voucher']} and is_manager=1");
               	        $voucher_user0 = Cas_Model_User_Voucher::instance()->update(array('voucher_money'=>$voucher_money),"id={$res['data']['voucher']} and is_manager=1");
               	    }else{
               	        //推广金使用一次
               	        $voucher_user = Cas_Model_User_Voucher::instance()->update(array('voucher_money'=>$voucher_money),"id={$res['data']['voucher']} and is_manager=1");
               	    }
               	    
               	    //发放佣金
               	    self::brokerage($res['data']['order_no']);
               	    //修改用户表
               	    $update_user = Cas_Model_User::instance()->update(array('is_market'=>1),"userid = {$user[0]['parent_id']}");
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
        if (! isset($params['voucher']) || strlen($params['voucher']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '优惠券id未提供';
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
    //佣金处理
    public static function brokerage($order_no)
    {
        //查订单
        $goods_order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order_no}'");
        //查用户
        $user_order =Cas_Model_User::instance()->fetchByWhere("userid = '{$goods_order[0]['userid']}'");
        //查产品
        $good_pay = Goods_Model_List::instance()->fetchByWhere("goods_id='{$goods_order[0]['goods_id']}'");
        if($good_pay['is_manager']=1){
            //佣金
            $parents =  Cas_Model_User::instance()->fetchParents($goods_order[0]['userid']);
            if(!empty($parents)){
                //一级客户
                if($parents[0]['one']){
                    $data['userid'] = $parents[0]['one'];//用户id
                    $data['user_grade'] = 1;//客户等级
                    //查一级客户
                    $user_one = Cas_Model_User::instance()->fetchByWhere("userid ='{$parents[0]['one']}' and status=0");
                    $data['username'] = $user_order[0]['username'];//客户名称
                    //var_dump( $data['username']);die;
                    //查订单
                    $order_pay = Bts_Model_Order::instance()->fetchByWhere("order_no='{$order_no}'");
                    $data['order_id'] = $order_pay[0]['order_id'];//订单id
                    $data['investment_amount'] = $order_pay[0]['buy_money'];//投资金额
                    $data['order_time'] = $order_pay[0]['ctime'];//下单时间
                    //查佣金设定表
                    $brokerage_setting = Brokerage_Model_Setting::instance()->fetchByWhere("1=1");
                    //产品佣金比例*佣金设定比例
                    $data['brokerage_ratio'] = $good_pay[0]['goods_broratio']*0.01*$brokerage_setting[0]['first_brokerage'];
                    //提成计算
                    $data['expected_money'] = round($brokerage_setting[0]['first_brokerage']*0.01*$order_pay[0]['buy_money']*$good_pay[0]['goods_broratio']*0.01,2);//预计提成
                    $data['brokerage_status'] = 1;//佣金状态
                    Cas_Model_User_Brokerage::instance()->addForEntity($data);
                }
                //二级客户
                if($parents[0]['two']){
                    $data['userid'] = $parents[0]['two'];//用户id
                    $data['user_grade'] = 2;//客户等级
                    //查一级客户
                    $user_one = Cas_Model_User::instance()->fetchByWhere("userid ='{$parents[0]['two']}' and status=0");
                    $data['username'] = $user_order[0]['username'];//客户名称
                    //var_dump( $data['username']);die;
                    //查订单
                    $order_pay = Bts_Model_Order::instance()->fetchByWhere("order_no='{$order_no}'");
                    $data['order_id'] = $order_pay[0]['order_id'];//订单id
                    $data['investment_amount'] = $order_pay[0]['buy_money'];//投资金额
                    $data['order_time'] = $order_pay[0]['ctime'];//下单时间
                    //产品佣金比例*佣金设定比例
                    $data['brokerage_ratio'] = $good_pay[0]['goods_broratio']*0.01*$brokerage_setting[0]['second_brokerage'];
                    //查佣金设定表
                    $brokerage_setting = Brokerage_Model_Setting::instance()->fetchByWhere("1=1");
                    //提成计算
                    $data['expected_money'] = round($brokerage_setting[0]['second_brokerage']*0.01*$order_pay[0]['buy_money']*$good_pay[0]['goods_broratio']*0.01,2);//预计提成
                    $data['brokerage_status'] = 1;//佣金状态
                    Cas_Model_User_Brokerage::instance()->addForEntity($data);
                }
                //三级客户
                if($parents[0]['three']){
                    $data['userid'] = $parents[0]['three'];//用户id
                    $data['user_grade'] = 3;//客户等级
                    //查一级客户
                    $user_one = Cas_Model_User::instance()->fetchByWhere("userid ='{$parents[0]['three']}' and status=0");
                    $data['username'] = $user_order[0]['username'];//客户名称
                    //var_dump( $data['username']);die;
                    //查订单
                    $order_pay = Bts_Model_Order::instance()->fetchByWhere("order_no='{$order_no}'");
                    $data['order_id'] = $order_pay[0]['order_id'];//订单id
                    $data['investment_amount'] = $order_pay[0]['buy_money'];//投资金额
                    $data['order_time'] = $order_pay[0]['ctime'];//下单时间
                    //查佣金设定表
                    $brokerage_setting = Brokerage_Model_Setting::instance()->fetchByWhere("1=1");
                    //产品佣金比例*佣金设定比例
                    $data['brokerage_ratio'] = $good_pay[0]['goods_broratio']*0.01*$brokerage_setting[0]['third_brokerage'];//产品佣金比例
                    //提成计算
                    $data['expected_money'] = round($brokerage_setting[0]['third_brokerage']*0.01*$order_pay[0]['buy_money']*$good_pay[0]['goods_broratio']*0.01,2);//预计提成
                    $data['brokerage_status'] = 1;//佣金状态
                    Cas_Model_User_Brokerage::instance()->addForEntity($data);
                }
            }
        }
    }
}

// End ^ Native EOL ^ encoding
