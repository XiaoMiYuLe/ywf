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
 * 获取用户代金券
 */
class Api_Cas_GetUserVoucher
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
		        
		        /*判断用户是否有代金券*/
		        if (! $user = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$res['data']['userid']}")) {
		            throw new Zeed_Exception('该用户没有代金券');
		        }else{
		            $voucher = Voucher_Model_Content::instance()->fetchByWhere("voucher_id = {$user[0]['voucher_id']}");
		        
    		        /*判断代金券是否过期*/
    		        $nowtime = date(DATETIME_FORMAT);
    		        if(strtotime($nowtime)>strtotime($user[0]['valid_data'])){
    		            $arr = array(
    		                    'voucher_status' => 3,//已过期
    		            );
    		            Cas_Model_User_Voucher::instance()->update($arr, "userid = {$res['data']['userid']}");
    		            $arr = array(
    		                    'disabled' => 2,//失效
    		            );
    		            Voucher_Model_Content::instance()->update($arr, "voucher_id = {$voucher[0]['voucher_id']}");
    		        }
		        }
		        /* 查询条件 */
		        $where = "userid = {$res['data']['userid']}";
		        if($res['data']['voucher_status'] == 1){ //1 ：未使用 2：已使用 3 ：已过期
		            $where .= " AND voucher_status=1";
		        }elseif($res['data']['voucher_status'] == 2){
		            $where .= " AND voucher_status=2";
		        }elseif($res['data']['voucher_status'] == 3){
		            $where .= " AND voucher_status=3";
		        }
		        
		        $voucher = Cas_Model_User_Voucher::instance()->GetContentByUserid($where , $order, $perpage, $offset);
		         if(!empty($voucher)){
                    foreach($voucher as $k=>&$v){
                        if($v['valid_data']){
                            /* 处理有效时间  */
                            $v['valid_data'] = date('Y-m-d',strtotime("{$v['valid_data']}"));
                            }
                        }
                    }

		        $res['data']['voucher'] = $voucher ? $voucher : array();
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
            self::$_res['error']  = '参数 userid 未提供';
            return self::$_res;
        }
        if (! isset($params['voucher_status'])) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 voucher_status 未提供';
            return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
