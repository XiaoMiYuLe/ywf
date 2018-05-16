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
 * 利息和兑付本金
 * @author Administrator
 *
 */
class Support_Reapal_pay_Interest
{
    /**
     * 默认返回数据
     */
	protected static $_res = array('status' => 0, 'msg' => '', 'data' => '');
	
    public static function run($params = null)
    {
        /* 验证是否有数据  */
		$res = self::validate($params);
		if ($res['status'] == 0) {
			try {
                //利息
                $order = Bts_Model_Order::instance()->fetchByWhere("order_status=2");
                if($order){
                    foreach ($order as $v){
                        $now = strtotime(date("Y-m-d"));//当前时间
                        
                        $order_start_time = $v['start_time'];//订单起息时间
                        $order_end_time = $v['end_time'];//订单结息时间
                        
                        //起息时间+1天   时间戳
                        $start_time = self::timeStamp($order_start_time,1);
                        //结束计息时间 结息时间+1天   时间戳
                        $end_time = self::timeStamp($order_end_time,1);
                        //昨日日期
                        $yesterday = date("Y-m-d",strtotime("-1 day"));
                        //每日派息 在起息和计息结束时间之间
                        if($v['deal_status']==1 && $now >=$start_time &&  $now <= $end_time){
                            //每日派息 年化收益率*购买金额取两位小数
                            $bts_yield = round(($v['yield']/365)*0.01*1*$v['buy_money'],2);//每日利息
                        
                            $userid = $v['userid'];//用户id
                            $asset = self::calculate_asset_plus($userid, $bts_yield);//余额变化
                            $order_no = $v['order_no'];//订单号
                            $total_fee = $bts_yield;//每日利 息
                            $status = '+';//资金状态
                            $type = 4;//记录类型
                            $pay_type =$v['pay_type'];//支付类型
                            $order_id = $v['order_id'];//订单id
                            $interest_time = $yesterday;
                            
                            $data= array(
                                'userid'=>$userid,
                                'order_no'=>$order_no,
                                'settlement_money'=>$total_fee,
                                'stime'=>$yesterday,//计息时间
                                'ctime'=>date("Y-m-d H:i:s"),
                                'settlement_status'=>1,
                            );
                            
                            $exist_log = Cas_Model_Record_Log::instance()->fetchByWhere("order_no='{$order_no}' and interest_time='{$yesterday}'");
                            
                            if(empty($exist_log)){
                                /*结息记录表*/
                                Interest_Model_Settlement::instance()->addForEntity($data);
                                /*资金明细表*/
                                self::record_log($asset, $userid, $order_no, $total_fee, $status, $type, $pay_type, $order_id,$interest_time);
                                /*修改账户余额+*/
                                self::asset($userid, $total_fee);
                                /*累计收益+*/
                                self::earnings($userid, $total_fee);
                            }
                        }
                        
                        //每日派息在计息结束之后,将订单状态改为已结息
                        if($now >=$end_time){
                            $order_now = Bts_Model_Order::instance()->update(array('order_status'=>'3'),"order_id = {$v['order_id']} and order_status = 2");
                            
                        }
                    }
                }
                
                //状态为3的订单 兑付
                $order_status3 = Bts_Model_Order::instance()->fetchByWhere("order_status=3");
                if($order_status3){
                    /*新手和债权产品*/
                   
                    foreach ($order_status3 as $v){
                        $now3 = strtotime(date("Y-m-d"));//当前时间
                        $status3_end_time = $v['end_time'];//状态3结息时间
                        $status3_cash_time = $v['cash_time'];
                        //结束计息时间 结息时间+1天   时间戳
                        $status3_endtime = self::timeStamp($status3_end_time,1);
                        //兑付日期    时间戳
                        $cashtime = self::timeStamp($status3_cash_time,0);

                        $order_id = $v['order_id'];//订单id

                        if($now3>=$cashtime){
                            /*修改订单状态为4,已兑付*/
                            $order_status4 = Bts_Model_Order::instance()->update(array('order_status'=>'4'),"order_id = {$order_id} and order_status = 3");
                            
                        }
                        //兑付
                        if($v['principal_status']==1 && $now3>=$cashtime){
                            //每日派息 年化收益率*购买金额取两位小数
                            $buy_money = $v['buy_money'];//本金
                
                            $userid =  $v['userid'];//用户id
                            $asset = self::calculate_asset_plus($userid, $buy_money);//余额变化
                            $order_no = $v['order_no'];//订单号
                            $total_fee = $buy_money;//本金
                            $status = '+';//资金状态
                            $type = 6;//记录类型
                            $pay_type =$v['pay_type'];//支付类型
                            //$order_id = $v['order_id'];//订单id

                            $data_cash= array(
                                'userid'=>$userid,
                                'order_no'=>$order_no,
                                'buy_money'=>$total_fee,
                                'cash_time'=>date("Y-m-d H:i:s"),
                                'cash_status'=>1,
                            );
                            /*结息记录表*/
                            Cash_Model_List::instance()->addForEntity($data_cash);
                            /*资金明细表*/
                            self::record_log($asset, $userid, $order_no, $total_fee, $status, $type, $pay_type, $order_id);
                            /*修改账户余额+*/
                            self::asset($userid, $total_fee);
                            /*修改订单状态为4,已兑付*/
                            //$order_status4 = Bts_Model_Order::instance()->update(array('order_status'=>'4'),"order_id = {$order_id} and order_status = 3");
                        }

                        

                    }
                     
                }
                 
                
                
            /* 返回错误信息  */
			} catch(Zeed_Exception $e) {
				$res['status'] = 1;
				$res['error'] = '错误信息：' . $e->getMessage();
				return $res;
			}
		}
		/* 返回数据 */
		return $res;
	}
	
	/**
	 * 验证参数
	 */
	public static function validate($params)
	{
		self::$_res['data'] = $params;
		return self::$_res;
	}
	
	//资金流水表
	public static function record_log($asset,$userid,$order_no,$total_fee,$status,$type,$pay_type,$order_id,$interest_time){
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
	    $data['interest_time'] = $interest_time;
	    $log = Cas_Model_Record_Log::instance()->addForEntity($data);
	    if(!$log){
	        return false;
	    }
	    return $log;
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
	//加累计收益
	public static function earnings($userid,$total_fee){
	    $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
	    $earnings = $ExistUser[0]['earnings'] +$total_fee;
	    $user = Cas_Model_User::instance()->update(array('earnings'=> $earnings ),"userid='{$userid}' and status=0");
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
	
	//计算累计收益 +
	public static function calculate_earnings_plus($userid,$total_fee){
	    $ExistUser = Cas_Model_User::instance()->fetchByWhere("userid='{$userid}' and status=0");
	    $asset = $ExistUser[0]['earnings'] +$total_fee;
	    return $asset;
	}
	//返回日期加n天的时间戳
	public static function timeStamp($time,$days){
	    $y = explode('-', $time);
	    $fulltime = mktime(0,0,0,$y[1],$y[2]+$days,$y[0]);
	    $new_fulltime = date('Y-m-d',$fulltime);
	    //时间戳
	    $new_fulltime = strtotime($new_fulltime);
	    return $new_fulltime;
	}
}