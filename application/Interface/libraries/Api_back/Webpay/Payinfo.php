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
 * B2C支付表单信息
 * @author Administrator
 *
 */
class Api_Webpay_Payinfo
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
			    $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['userid']}'");
			    if (!$userExists) {
			        throw new Zeed_Exception('该用户不存在');
			    }
			    
			    /* 检查用户状态 */
			    if($userExists[0]['status'] == 1 ){
			        throw new Zeed_Exception('该账号已禁用');
			    }
			    
			    $userExists = current($userExists);
			    
			    if(empty($userExists['bank_id'])){
			        throw new Zeed_Exception('未绑卡');
			    }
			     
			    /*绑定银行卡信息*/
	            $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$userExists['bank_id']}' and is_use=1 and is_del=0");
	            if(empty($bank_info)){
	                throw new Zeed_Exception('未绑卡');
	            }
			   
			    $urlmapping = Zeed_Config::loadGroup('urlmapping');
			    
			    //支付信息
			    $_order_id = trim($res['data']["order_id"]); //商户订单号
			    $_order_amt = trim($res['data']["order_amt"]); //交易金额
			    $_order_pay_type = trim($res['data']["order_pay_type"]); //支付类型
			    $_iss_ins_cd = trim($res['data']["iss_ins_cd"]); //银行
			    			 
			  
			    $_page_notify_url = $urlmapping['store_url_login']."/cas/recharge/prompt"; //页面跳转URL
			    $_back_notify_url = "$urlmapping[store_url_login]"."/index/WebCallBack/wangyin"; //后台通知URL
			    $_order_valid_time = ""; //超时时间
			    $_mchnt_cd = "0001000F0040992"; //商户代码
			    $_mchnt_key = "vau6p7ldawpezyaugc0kopdrrwm4gkpu"; //商户代码
			    $_goods_name = ""; //商品名称
			    $_goods_display_url = ""; //商品展示网址
			    $_rem = ""; //备注
			    $_ver = "1.0.1"; //版本号
			    
			    //拼接数据
			    $_data = "";
			    $_data .= $_mchnt_cd."|";
			    $_data .= $_order_id."|";
			    $_data .= $_order_amt."|";
			    $_data .= $_order_pay_type."|";
			    $_data .= $_page_notify_url."|";
			    $_data .= $_back_notify_url."|";
			    $_data .= $_order_valid_time."|";
			    $_data .= $_iss_ins_cd."|";
			    $_data .= $_goods_name."|";
			    $_data .= $_goods_display_url."|";
			    $_data .= $_rem."|";
			    $_data .= $_ver."|";
			    $_data .= $_mchnt_key;
			    
			    $_md5 = MD5($_data); //签名数据
			    
			    //返回参数
			    $data['md5'] = $_md5;
			    $data['mchnt_cd'] = $_mchnt_cd;
			    $data['order_id'] = $_order_id;
			    $data['order_amt'] = $_order_amt;
			    $data['order_pay_type'] = $_order_pay_type;
			    $data['page_notify_url'] = $_page_notify_url;
			    $data['back_notify_url'] = $_back_notify_url;
			    $data['order_valid_time'] = $_order_valid_time;
			    $data['iss_ins_cd'] = $_iss_ins_cd;
			    $data['goods_name'] = $_goods_name;
			    $data['goods_display_url'] = $_goods_display_url;
			    $data['rem'] = $_rem;
			    $data['ver'] = $_ver;
			    
			    //展示
			    $data['order_money'] = $_order_amt/100;
			    
			    //cas_pay表
			    $data_pay['userid'] = $res['data']['userid'];
			    $data_pay['order_no'] = $_order_id;
			    $data_pay['type'] = 3;
			    $data_pay['ctime'] =  date(DATETIME_FORMAT);
			    $data_pay['pay_type'] = 2;
			    $data_pay = Cas_Model_Pay::instance()->addForEntity($data_pay);
			    
			    $res['data']=$data;
			    
            /* 返回错误信息  */
			} catch(Zeed_Exception $e) {
				$res['status'] = 1;
				$res['error'] = '错误信息：' . $e->getMessage();
				return $res;
			} catch(Exception $e) {
				$res['status'] = 1;
				$res['error'] = '错误信息：网络请求异常，请稍后再试';
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
	    if (!isset($params['userid']) || ! $params['userid']) {
	        self::$_res['status'] = 1;
	        self::$_res['error'] = '用户id未提供';
	        return self::$_res;
	    }
		if (!isset($params['order_id']) || ! $params['order_id']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '商户订单号未提供';
			return self::$_res;
		}
		if (!isset($params['order_amt']) || ! $params['order_amt']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '交易金额';
			return self::$_res;
		}
		if (!isset($params['order_pay_type']) || ! $params['order_pay_type']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '支付类型未提供';
			return self::$_res;
		}
		if (!isset($params['iss_ins_cd']) || ! $params['iss_ins_cd']) {
			self::$_res['status'] = 1;
			self::$_res['error'] = '银行代码未提供';
			return self::$_res;
		}

		self::$_res['data'] = $params;
		return self::$_res;
	}
}