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
 * 获取用户信息
 */
class Api_Cas_GetUserInfo
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_allowFields = array('userid');
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
                $userExists = Cas_Model_User::instance()->fetchByWhere( "userid= '{$res['data']['userid']}'");
    	        if (!$userExists) {
    	            throw new Zeed_Exception('该用户不存在，请重新输入');
    	        }
    	        
    	        /* 检查用户状态 */
    	        if($userExists[0]['status'] == 1 ){
    	            throw new Zeed_Exception('该账号已禁用，请重新输入');
    	        }
    	        
    	        $userExists = current($userExists);
    	        
    	        /*绑定银行卡信息*/
    	        if(!empty($userExists)){
    	            if(!empty($userExists['bank_id'])){
    	                $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$userExists['bank_id']}' and is_use=1 and is_del=0");
    	                if(!empty($bank_info)){
    	                    $is_tiecard= 1;
    	                }else{
    	                    $is_tiecard= 0;
    	                }
    	            }else{
    	                $is_tiecard= 0;
    	            }
    	        }
    	        
    	        //是否设置交易密码
    	        if (!empty($userExists['pay_pwd'])) {
    	            $is_pay_pwd = 1;
    	        } else {
    	            $is_pay_pwd = 0;
    	        }
    	        
    	        /*推荐人手机号*/
    	        if(!empty($userExists)){
    	            $recommender = Cas_Model_User::instance()->fetchByWhere("userid = '{$userExists['parent_id']}'");
    	        }
    	        
		        /* 返回用户基本信息 */
		        $user = array(
		                'userid'  =>$userExists['userid'],
		                'bank_id' =>$userExists['bank_id'],
		        		'phone' => $userExists['phone'],
		                'idcard' => $userExists['idcard'],
		        		'username' => $userExists['username'],
		                'bank_no' => $bank_info[0]['bank_no']?$bank_info[0]['bank_no']:'',
                        'recommender' =>$recommender[0]['phone'],
		        );
		        
		       $phone = substr($user['phone'],0,3).'****'. substr($user['phone'],-4);
		       $uername  = $user['username']?$user['username']:'';
	
		       if(!empty($user['idcard'])){
		           $idcard = '(****'.substr($user['idcard'], -4).')';
		       }else{
		           $idcard = '';
		       }
		       
		       if(!empty($user['bank_no'])){
		            $bank_no = '(****'.substr($user['bank_no'], -4).')';
		       }else{
		           $bank_no = '';
		       }
		    
		       if(!empty($user['recommender'])){
		           $recommender = substr($user['recommender'],0,3).'****'. substr($user['recommender'],-4);
		       }else{
		           $recommender = '';
		       }
		       
		       $data = array(
		           'userid'=>$user['userid'],
		           'bank_id'=>$user['bank_id'],
		           'phone'=>$phone,
		           'username'=>$username,
		           'idcard'=>$idcard,
		           'bank_no'=>$bank_no,
		           'recommender'=>$recommender ,
		           'contacts_person'=>$userExists['contacts_person'] ,
		           'contacts_phone'=>$userExists['contacts_phone'] ,
		           'zip_code'=>$userExists['zip_code'] ,
		           'address'=>$userExists['address'] ,
		           'is_tiecard' => $is_tiecard,
		           'is_pay_pwd'=>$is_pay_pwd,
		       );
		       
		        $res['data'] = $data;
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取用户信息失败。错误信息：' . $e->getMessage();
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
            self::$_res['error']  = '用户id未提供';
            return self::$_res;
        }
       
        /* 组织数据 */
        $set = array();
        foreach (self::$_allowFields as $f) {
            $set[$f] = isset($params[$f]) ? $params[$f] : null;
        }
        self::$_res['data'] = $set;
        
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
