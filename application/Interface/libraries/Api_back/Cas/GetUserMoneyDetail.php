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
 * 获取用户资金详情
 */
class Api_Cas_GetUserMoneyDetail
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
               	if (!$user = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
               		throw new Zeed_Exception('该用户不存在或已被冻结');
               	}
               	$data = array();
               	$data['asset_sum'] = $user[0]['asset'];
               	$data['asset'] = number_format($user[0]['asset'],2);
               	
            	$record = Cas_Model_Record_Log::instance()->fetchByWhere("userid = {$res['data']['userid']} and type in (4,5)","ctime desc",null,null,'money');
            	$money = 0;
            	if ($record) {
            		foreach ($record as $k => $v) {
            			$money += $v['money'];
            		}
            	}
            	$data['count_income'] = number_format($money,2);
            	
            	//昨日收益
            	/* $now = date(DATETIME_FORMAT);
            	$arr = explode(" ",$now);
            	$y = explode("-",$arr[0]);
            	$s = explode(":",$arr[1]);
            	$fulltime = mktime(0,0,0,$y[1],$y[2],$y[0]);
            	$fulltime = date("Y-m-d H:i:s",$fulltime);
            	$time = mktime(0,0,0,$y[1],$y[2]-1,$y[0]);
            	$time = date("Y-m-d H:i:s", $time);
            	$where = "userid = {$res['data']['userid']} and type in (4,5) and ctime < '{$fulltime}' and ctime > '{$time}'";
            	$last = Cas_Model_Record_Log::instance()->fetchByWhere($where);
               	$m = 0;
               	if ($last) {
               		foreach ($last as $k => &$v) {
               			$m += $v['money'];
               		}
               	}
               	$data['last_income'] = number_format($m,2); */
               	
            	//昨日收益
               	$yesterday = date("Y-m-d",strtotime("-1 day"));
               	$where = "userid = {$res['data']['userid']} and type in (4,5) and interest_time ='{$yesterday}'";
               	$last = Cas_Model_Record_Log::instance()->fetchByWhere($where);
               	$m = 0;
               	if ($last) {
               	    foreach ($last as $k => &$v) {
               	        $m += $v['money'];
               	    }
               	}
               	$data['last_income'] = number_format($m,2);
               	
               	
               	
               	$order = Bts_Model_Order::instance()->fetchByWhere("userid = {$res['data']['userid']} and is_pay = 1 and is_del = 0 and order_status <> 4 and principal_status=1");
               	$count = Bts_Model_Order::instance()->getCount("userid = {$res['data']['userid']}  and is_del = 0 and order_status <> 4 and order_status <> 5");
               	//冻结资金
               	$withdraw = Withdraw_Model_List::instance()->fetchByWhere("userid='{$res['data']['userid']}' and withdraw_status=1");
               	$i = 0;
               	if ($withdraw) {
               		foreach ($withdraw as $k => &$v) {
               			$i += $v['withdraw_money'];
               		}
               	}
               	$n = 0;
               	if ($order) {
               		foreach ($order as $k => &$v) {
               			$n += $v['buy_money'];
               		}
               	}
               	$total = $user[0]['asset'] + $n + $i;
               	$data['total'] = number_format($total,2);
               	$data['goods_num'] = $count;
               	
               	$voucher = Cas_Model_User_Voucher::instance()->getCount("userid = {$res['data']['userid']} and voucher_status = 1 and valid_data > '{$now}'");
               	$data['voucher_num'] = $voucher;

               	
               	$user = current($user);
               	/*绑定银行卡信息*/
               	if(!empty($user)){
               	    if(!empty($user['bank_id'])){
               	        $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$user['bank_id']}' and is_use=1 and is_del=0");
               	        if(!empty($bank_info)){
               	            $data['is_tiecard'] = 1;
               	        }else{
               	            $data['is_tiecard'] = 0;
               	        }
               	    }else{
               	         $data['is_tiecard'] = 0;
               	    }
               	}
               	
               	$res['data'] = $data;
               	
            } catch (Zeed_Exception $e) {
                self::$_res['status'] = 1;
                self::$_res['error'] = '获取用户资金详情出错。错误信息：' . $e->getMessage();
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
        if (! isset($params['userid']) || strlen($params['userid']) < 1) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数用户ID userid 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
