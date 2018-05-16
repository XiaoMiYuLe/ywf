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
 * 储蓄卡签约接口（绑卡）
 * @author Administrator
 *
 */
class Api_Pay_Debit
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
			    /* 检查用户是否存在 */
			    $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['member_id']}'");
			    if (!$userExists) {
			        throw new Zeed_Exception('该用户不存在');
			    }
			    
			    /* 检查用户状态 */
			    if($userExists[0]['status'] == 1 ){
			        throw new Zeed_Exception('该账号已禁用');
			    }
			    
			    $userExists = current($userExists);
			    
			    /*绑定银行卡信息*/
			     $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_no='{$res['data']['card_no']}' and is_use=1 and is_del=0");
			    
			    if($bank_info){
			        throw new Zeed_Exception('此银行卡已经被用户绑定');
			    }
			     
			    /*失效的银行卡不给绑定*/
			    $bank_before = Cas_Model_Bank::instance()->fetchByWhere("bank_no='{$res['data']['card_no']}'and is_del=1");
			    if($bank_before){
			        throw new Zeed_Exception('该银行卡已经失效,请联系客服');
			    }
			    
			    $set = Support_Reapal_debit_debitResult::run($res['data']);
			    if ($set['status'] != 0000) {
			    	throw new Zeed_Exception($set['error']);
			    }
			    $res['data'] = $set['data'];
			    $res['error'] = $set['error'];
			    
			    //插入银行表
			    $bank['bind_id'] = $res['data']->bind_id;//绑卡id
			    $bank['bank_code'] = $res['data']->bank_code;//银行编码
			    $bank['bank_no'] = $params['card_no'];//银行卡号
			    $bank['userid'] =  (int) $params['member_id'];//用户id
			    $bank['bank_name'] = $res['data']->bank_name;//银行名称
			    $bank['cardholder'] = $params['owner'];//持卡人
			    $bank['ctime'] = date(DATETIME_FORMAT);//创建日期
			    $bank['mtime'] = date(DATETIME_FORMAT);//修改日期
			    $bank['subbank_name'] = $params['subbank_name'] ?$params['subbank_name']:'';//分行名称
			    $bank['is_use'] = 0 ;//是否使用
			    $bank['phonebankcard'] = $params['phone'];//银行卡绑卡手机号
			    //如表cas_bank 返回bank_id
			    $bankid = Cas_Model_Bank::instance()->addForEntity($bank);
               
			    if(empty($bankid)){
			        throw new Zeed_Exception('储蓄卡签约失败');
			    }
			    
			    //修改用户信息
			    $id = Cas_Model_User::instance()->update(array('username'=>$bank['cardholder'],'idcard'=>$params['cert_no'],'bank_id'=>$bankid),"userid= '{$bank['userid']}'");
			        
			    
			    $data['bank_id'] = $bankid;//银行卡id
			    $data['username'] = $params['owner'];//用户名
			    $data['idcard'] = $params['cert_no'];//身份证号
			    $data['mtime'] = date(DATETIME_FORMAT);//修改日期
			    $data['order_no'] = $res['data']->order_no;//商户订单号
			    $data['bind_id'] = $bank['bind_id'];//绑卡id
			    
			    $res['data']=$data;
			    
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
	    /* 验证是否有数据  */
		if (!isset($params['merchant_id']) || ! $params['merchant_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户号未提供';
			return self::$_res;
		}
		if (!isset($params['card_no']) || ! $params['card_no']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '银行卡号未提供';
			return self::$_res;
		}
		if (!isset($params['owner']) || ! $params['owner']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '姓名未提供';
			return self::$_res;
		}
		if (!isset($params['member_id']) || ! $params['member_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '会员号未提供';
			return self::$_res;
		}
		if (!isset($params['cert_no']) || ! $params['cert_no']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '证件号码未提供';
			return self::$_res;
		}
		if (!isset($params['phone']) || ! $params['phone']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '手机号未提供';
			return self::$_res;
		}
		if (!isset($params['total_fee']) || ! $params['total_fee']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '交易金额未提供';
			return self::$_res;
		}
	
		
		self::$_res['data'] = $params;
		return self::$_res;
	}
}