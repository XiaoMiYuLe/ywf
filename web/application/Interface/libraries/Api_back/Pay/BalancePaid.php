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
 * 用户余额支付
 * 
 */
class Api_Pay_BalancePaid
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
    public static function run($params = null)
    {
        $res = self::validate($params);
        
        if ($res['status'] == 0) {
            
            try {
                /* 检查用户是否存在 */
                if (! $userExists = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['member_id']} and status = 0")) {
                    throw new Zeed_Exception('该用户不存在或被冻结');
                }else{
                    /* 判断交易密码 */
                    if(empty($userExists[0]['pay_pwd'])){
                        throw new Zeed_Exception('该用户没有交易密码');
                    }
                }
                $pay = Cas_Model_Pay::instance()->fetchByWhere("order_no = '{$res['data']['order_no']}'");
                if(empty($pay)){
                    /*支付记录表cas_pay*/
                    $data['userid'] = $res['data']['member_id'];
                    $data['order_no'] = $res['data']['order_no'];
                    $data['type'] = 2;
                    $data['ctime'] =  date(DATETIME_FORMAT);
                     
                    $id = Cas_Model_Pay::instance()->addForEntity($data);
                    if(empty($id)){
                        throw new Zeed_Exception('支付失败');
                    }
                }

                /*交易密码是否正确*/
                $oldpay_pwd= md5($res['data']['pay_pwd']);
               
                if($oldpay_pwd !=$userExists[0]['pay_pwd']){
                    throw new Zeed_Exception('交易密码不正确');
                }else{
                    //是否为转让订单
                    $transfer = Cas_Model_Transfer::instance()->fetchByWhere("buy_order_no = {$res['data']['order_no']}");
                    //返查订单
                    if(!empty($transfer)){
                        $transfer_order = Bts_Model_Order::instance()->fetchByWhere("order_no = {$transfer[0]['transfer_order_no']}");
                        if($transfer_order[0]['transfer_status']==3){
                            throw new Zeed_Exception('该订单已在转让中');
                        }
                    }
                    
                    /*查支付记录表*/
                    $order = Cas_Model_Pay::instance()->fetchByWhere("order_no = '{$res['data']['order_no']}'");
                    if(!empty($order[0])){
                        $order = $order[0];
                        //余额不足
                        $asset_user = self::calculate_asset($order['userid']);
                        if($asset_user<$res['data']['total_fee']){
                            throw new Zeed_Exception('余额不足,请去充值');
                        } 
                        //支付类型：2：订单支付 
                        if($order['type']==2){
                            $goods_order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order['order_no']}'");
                            $goods_order = $goods_order[0];
                            //不同的产品模式 bts_order表 is_pay=1  新手和直购 ：order_status=2 ，预约order_status=1
                            if(!empty($goods_order)){
                                if($goods_order['goods_pattern']==1 || $goods_order['goods_pattern']==2){
                                    //是否付款  订单状态2：计息中   实付金额  real_money
                                    $paytime = date('Y-m-d H:i:s');
                                    $update_order = Bts_Model_Order::instance()->update(array('is_pay'=>1,'pay_type'=>1,'pay_time'=>$paytime,'is_del'=>0,'order_status'=>2),"order_no = '{$order['order_no']}' and is_del=1 and is_pay=0");
                                
                                    //兑付转让订单
                                    if($goods_order['is_transfer_order']==1){
                                        if($goods_order['is_transfer_order']){
                                            Cas_Model_Transfer::instance()->update(array("pay_status"=>1),"buy_order_no='{$goods_order['order_no']}'");
                                            $transfer = Cas_Model_Transfer::instance()->fetchByWhere("buy_order_no='{$goods_order['order_no']}'");
                                            if(!empty($transfer)){
                                                //兑付
                                                $order_cash = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$transfer[0]['transfer_order_no']}'");
                                    
                                                if($order_cash[0]['order_status'] !=4){
                                    
                                                    $buy_money = $order_cash[0]['transfer_price'];//本金
                                    
                                                    $userid =  $order_cash[0]['userid'];//用户id
                                                    $asset = self::calculate_asset_plus($userid, $buy_money);//余额变化
                                                    $order_no = $order_cash[0]['order_no'];//订单号
                                                    $total_fee = $buy_money;//本金
                                                    $status = '+';//资金状态
                                                    $type = 8;//记录类型
                                                    $pay_type =$order_cash[0]['pay_type'];//支付类型
                                    
                                                    $data_cash= array(
                                                        'userid'=>$userid,
                                                        'order_no'=>$order_no,
                                                        'buy_money'=>$total_fee,
                                                        'cash_time'=>date("Y-m-d H:i:s"),
                                                        'cash_status'=>1,
                                                    );
                                    
                                                    /*兑付记录表*/
                                                    Cash_Model_List::instance()->addForEntity($data_cash);
                                    
                                                    /*资金明细表*/
                                                    $order_id = $order_cash[0]['order_id'];
                                                    self::record_log($asset, $userid, $order_no, $total_fee, $status, $type, $pay_type, $order_id, $interest_time);
                                                    /*修改账户余额+*/
                                                    self::asset($userid, $total_fee);
                                    
                                                    //扣除手续费
                                                    $asset_counter = self::calculate_asset_reduce($userid, $order_cash[0]['counter_money']);//余额变化
                                                    self::record_log($asset_counter, $userid, $order_no,  $order_cash[0]['counter_money'],'-', 7, $pay_type, $order_id, $interest_time);
                                                    /*修改用户余额-*/
                                                    self::asset_reduce($userid, $order_cash[0]['counter_money']);
                                                    /*更改状态*/
                                                    $order_status4 = Bts_Model_Order::instance()->update(array('order_status'=>'4','transfer_status'=>3),"order_no = '{$transfer[0]['transfer_order_no']}' and (order_status = 2 or order_status = 3)");
                                                }
                                    
                                            }
                                        }
                                    
                                    
                                    }
                                if($goods_order[0]['is_transfer_order']==0){
                                    //产品剩余额度
                                    $buy_money = $goods_order['buy_money'];
                                    $goods = Goods_Model_List::instance()->fetchByWhere("goods_id = '{$goods_order['goods_id']}' and is_del=0");
                                    $spare_fee = $goods[0]['spare_fee']-$buy_money;
                                    $goods_id = Goods_Model_List::instance()->update(array('spare_fee'=>$spare_fee),"goods_id ='{$goods_order['goods_id']}' and is_del=0");
                                    //购买人数
                                    self::addOne($goods_order['goods_id']);
                                    //是否售罄
                                    $goods_new = Goods_Model_List::instance()->fetchByWhere("goods_id = '{$goods_order['goods_id']}' and is_del=0");
                                    if($goods_new[0]['spare_fee']<$goods_new[0]['low_pay']){
                                        $goods_new_id =  $goods_id = Goods_Model_List::instance()->update(array('goods_status'=>2),"goods_id = '{$goods_order['goods_id']}' and is_del=0");
                                    }
                                }
                               
                                //计算余额 -
                                $calculate_asset = self::calculate_asset_reduce($order['userid'],  $res['data']['total_fee']);
                                //用户余额
                                if(($asset_reduce = self::asset_reduce($order['userid'], $res['data']['total_fee']))==false){
                                    throw new Zeed_Exception('支付失败');
                                }
                                
                                if($goods_order[0]['is_transfer_order']==0){
                                     //代金券状态处理 已使用:voucher_status=2
                                    if($goods_order['is_voucher']==1){
            			        	    $use_time = date(DATETIME_FORMAT);
            			        	    $voucher_status = Cas_Model_User_Voucher::instance()->update(array('voucher_status'=>2,'order_id'=>"{$goods_order['order_id']}",'use_time'=>"{$use_time}"),"id={$goods_order['voucher']}");
    			        	        }
                                    //佣金处理,新手类产品不存在佣金
                                    if($goods_order['goods_pattern']==2){
                                         self::brokerage($order['order_no']);
                                    }
                                }
                                //资金明细表
                                if(($log = self::record_log($calculate_asset, $order['userid'], $order['order_no'],$res['data']['total_fee'], '-', 3,1,$goods_order['order_id']))==false){
                                    throw new Zeed_Exception('支付失败');
                                }
                                
                                //分发优惠券
                                Cas_Model_User_Voucher::instance()->sendCouponByOrder($goods_order['userid'], floatval($goods_order['buy_money']));
                                //将用户置为非新手
                                Cas_Model_User::instance()->update(array('is_buy'=>1),"userid = {$order['userid']}");
                            }else{
                                throw new Zeed_Exception('订单不存在或已支付');
                            }
                            }
                        }
                    }
                }
                
                unset($res['data']['pay_pwd']);
                unset($res['data']['total_fee']);
                
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = "失败。错误信息：" . $e->getMessage();
                return $res;
            } catch(Exception $e) {
                $res['status'] = 1;
                $res['error'] = '错误信息：网络请求异常，请稍后再试';
                return $res;
            }
            
        }
        return $res;
    }
    
    //购买人数加1
    public static function addOne($goods_id){
        $good = Goods_Model_List::instance()->fetchByWhere("goods_id='{$goods_id}'");
        $num = $good[0]['buy_num']+1;
        Goods_Model_List::instance()->update(array('buy_num'=>$num),"goods_id = '{$goods_id}'");
    }
    
    //加用户余额
    public static function asset($userid,$total_fee){
        $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
        $asset = $ExistUser[0]['asset'] +$total_fee;
        $user = Cas_Model_User::instance()->update(array('asset'=>$asset),"userid='{$userid}' and status=0");
        if(!$user){
            return false;
        }
        return true;
    }
    
    //减用户余额
    public static function asset_reduce($userid,$total_fee){
        $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
        $asset = $ExistUser[0]['asset'] -$total_fee;
        $user = Cas_Model_User::instance()->update(array('asset'=>$asset),"userid='{$userid}' and status=0");
        if(!$user){
            return false;
        }
        return true;
    }
    
    //计算用户余额 +
	public static function calculate_asset_plus($userid,$total_fee){
	    $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
	    $asset = $ExistUser[0]['asset'] +$total_fee;
	    return $asset;
	}

	//计算用户余额  不变
	public static function calculate_asset($userid){
	    $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
	    $asset = $ExistUser[0]['asset'];
	    return $asset;
	}
	
	//计算用户余额-
	public static function calculate_asset_reduce($userid,$total_fee){
	    $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
	    $asset = $ExistUser[0]['asset'] -$total_fee;
	    return $asset;
	}
	
    //资金流水表
    public static function record_log($asset,$userid,$order_no,$total_fee,$status,$type,$pay_type,$order_id){
        $data['flow_asset'] = $asset;
        $data['userid'] = $userid;
        $data['order_no'] = $order_no;
        $data['money'] = $total_fee;
        $data['status'] = $status;
        $data['ctime'] = date('Y-m-d H:i:s');
        $data['mtime'] = date('Y-m-d H:i:s');
        $data['type'] = $type;
        $data['pay_type'] = $pay_type;
        $data['order_id'] = $order_id;
        $log = Cas_Model_Record_Log::instance()->addForEntity($data);
        if(!$log){
            return false;
        }
        return $log;
    }
    
//佣金处理
	public static function brokerage($order_no)
	{
	 $order = Cas_Model_Pay::instance()->fetchByWhere("order_no = '{$order_no}'");
         $order = $order[0];
         //查订单
         $goods_order = Bts_Model_Order::instance()->fetchByWhere("order_no = '{$order['order_no']}'");
         //查用户
         $user_order =Cas_Model_User::instance()->fetchByWhere("userid = '{$goods_order[0]['userid']}'");
         //查产品
         $good_pay = Goods_Model_List::instance()->fetchByWhere("goods_id='{$goods_order[0]['goods_id']}'");
         if($good_pay['is_new']!=1){
             //佣金
             $parents =  Cas_Model_User::instance()->fetchParents($order['userid']);
             if(!empty($parents)){
                 //一级客户
                 if($parents[0]['one'] && ($parents[0]['one']!=2446)){
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
                 if($parents[0]['two'] && ($parents[0]['two']!=2446)){
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
                 //二级客户
                 if($parents[0]['three'] && ($parents[0]['three']!=2446)){
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
    
    /**
     * 数据校验
     *
     * @param unknown $params
     * @return multitype:number string
     */
    public static function validate($params)
    {
        /* 验证是否有数据  */
		if (!isset($params['member_id']) || !$params['member_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '用户id未提供';
			return self::$_res;
		}
		if (!isset($params['order_no']) || !$params['order_no']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户订单号未提供';
			return self::$_res;
		}
		if (!isset($params['pay_pwd']) || strlen($params['pay_pwd']) < 1) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '交易密码未提供';
			return self::$_res;
		}
        if (!isset($params['total_fee']) || !$params['total_fee']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '支付金额未提供';
			return self::$_res;
		}
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
