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
 * 用户提现
 */
class Api_Cas_WithdrawDeposit
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
		        if (! $userExists = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
		            throw new Zeed_Exception('该用户不存在或被冻结');
		        }else{
		            /* 判断交易密码 */
		            if(empty($userExists[0]['pay_pwd'])){
		                throw new Zeed_Exception('该用户没有交易密码');
		            }
		            /* 判断银行卡  */
		            if(!empty($userExists[0]['bank_id'])){
		                $bank = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$userExists[0]['bank_id']}' and is_use=1 and is_del=0");
		                if(empty($bank)){
		                    throw new Zeed_Exception('该用户未绑定银行卡');
		                }
		            }else{
		                throw new Zeed_Exception('该用户未绑定银行卡');
		            }
		        }
		        
		        /*交易密码是否正确*/
		        $oldpay_pwd= md5($res['data']['pay_pwd']);
		        
		        if($oldpay_pwd !=$userExists[0]['pay_pwd']){
		            throw new Zeed_Exception('交易密码不正确');
		        }
		         
		        $arr['userid'] = $res['data']['userid'];
		        $arr['withdraw_money'] = $res['data']['withdraw_money'];  //提现金额
		        $arr['phone'] = $userExists[0]['phone'];     //用户手机号码
		        $arr['bank_name'] = $bank[0]['bank_name'];   //银行名称
		        $arr['bank_no'] = $bank[0]['bank_no'];       //银行卡号
		        $arr['opening_bank'] = $bank[0]['subbank_name'];  //开户行
		        $arr['withdraw_status'] = 1;    //提现状态为未处理
		        $arr['ctime'] = date(DATETIME_FORMAT);
		        
		        //处理用户余额    提现每月前2次免费，超过2次每笔2元
		        $nowtime = substr(date("Y-m-d",time()),0,7);
		        $nowdate = substr(date("Y-m-d",time()),0,7).'-01';
		        $order = "ctime DESC";
		        $where = "userid = {$res['data']['userid']} and ctime>='{$nowdate}'";
		        $count = Withdraw_Model_List::instance()->getCount($where);
		        if($count >= 2){
		            $arr['withdraw_poundage'] = 2;
		            $arr['asset'] = $userExists[0]['asset'] - ($arr['withdraw_money']); 
		            $arr['practical_withdraw_money'] = $arr['withdraw_money']-2;
		        }else{
		            /* 第一次提现  */
		            $arr['withdraw_poundage'] = 0;
		            $arr['asset'] = $userExists[0]['asset'] - $arr['withdraw_money'];
		            $arr['practical_withdraw_money'] = $arr['withdraw_money'];
		        }
		        if($arr['asset']<0.00){
		             throw new Zeed_Exception('用户余额不足!');
		        }else{
		            //更改用户表账户余额
		            $asset = array(
		                    'asset' => $arr['asset'],
		            );
		            Cas_Model_User::instance()->update($asset, "userid = {$res['data']['userid']}");
		        }
		        /* 记录提现信息 */

		        $withdraw = Withdraw_Model_List::instance()->addForEntity($arr);
		        
		        $res['data']['withdraw'] = $withdraw ? $withdraw : array();
		        
		        /* 记录提现到资金明细表  */
		        $user = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0");
		        if(!empty($user)){
		            $arrs['userid'] = $res['data']['userid'];
		            $arrs['flow_asset'] = $user[0]['asset'];
		            $arrs['money'] = $arr['withdraw_money'];
		            $arrs['status'] = "-";
		            $arrs['ctime'] = date(DATETIME_FORMAT);
		            $arrs['pay_type'] = 1;  // 支付方式    余额支付
		            $arrs['type'] = 2;      //记录类型   提现
		            Cas_Model_Record_Log::instance()->addForEntity($arrs);
		       }
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '提示:' . $e->getMessage();
        		return $res;
        	}
        }
        return $res;
    }
    
    /**
     * 验证参数
     */
    public static function validate($params)
    {
        /* 校验必填项 */
        if (! isset($params['userid']) || ! $params['userid']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 userid 未提供';
            return self::$_res;
        }
        
        if (! isset($params['pay_pwd']) || ! $params['pay_pwd']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 pay_pwd 未提供';
            return self::$_res;
        }
        
        if (! isset($params['withdraw_money']) || ! $params['withdraw_money']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 withdraw_money 未提供';
            return self::$_res;
        }
        
        if ($params['withdraw_money']< 2) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '提现金额不能少于2元';
            return self::$_res;
        }
        if ($params['withdraw_money']> 1000000) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '提现金额不能高于于1000000元';
            return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
