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
 * 获取用户可用代金券，加息券数量
 */
class Api_Two_GetJudgeVoucherCount
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
		        }
		        
		        if((in_array($res['data']['is_voucher'], array(1,2))==false)){
		          throw new Zeed_Exception('is_voucher参数无效');
		       }
		       
		       if(in_array($res['data']['is_interest'], array(1,2))==false){
		           throw new Zeed_Exception('is_interest参数无效');
		       }
		        
		        if($res['data']['is_voucher']==2 && $res['data']['is_interest']==2){
		            $res['data']['content'] = array();
		            return $res;
		        } 
                		        
		        $where = "userid = {$res['data']['userid']} AND voucher_status = 1";
		        
		        $str ='';
		        if($res['data']['is_voucher']==1){
		            $str = '1';
		        }
		        
		        if($res['data']['is_interest']==1){
		            $str .= trim($str) ? ',3':'3';
		        }
		        
		        $where .= " and type in($str)";
                 
		        $user_vouchers = Cas_Model_User_Voucher::instance()->fetchByWhere($where);
		        
		        if(!empty($user_vouchers)){
		            foreach ($user_vouchers as $k=>&$v){
		                if($v['voucher_status']==1){
		                    /*判断优惠券是否过期*/
		                    $nowtime = date(DATETIME_FORMAT);
		                    if(strtotime($nowtime)>strtotime($v['valid_data'])){
		                        $arr = array(
		                            'voucher_status' => 3,//已过期
		                        );
		                        Cas_Model_User_Voucher::instance()->update($arr, "id = {$v['id']}");
		                    }
		                }
		            }
		        
		        }
		        
		        if(!empty($user_vouchers)){
		            $res['data']['voucher_exist'] = '1';
		            $res['data']['num'] = (string)count($user_vouchers);
		        }else{
		            $res['data']['voucher_exist'] = '2';
		            $res['data']['num'] = '0';
		            $res['data']['voucher_exist'] = '0';
		        }
		        
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取用户优惠券失败。错误信息：' . $e->getMessage();
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
        if (! isset($params['is_voucher']) || ! $params['is_voucher']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 is_voucher 未提供';
            return self::$_res;
        }
        if (! isset($params['is_interest']) || ! $params['is_interest']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 is_interest 未提供';
            return self::$_res;
        } 
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
