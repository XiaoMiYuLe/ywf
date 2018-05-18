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
 * 获取用户优惠券列表
 */
class Api_Two_GetUserVoucher
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    protected static $_perpage = 20;
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
		        if (! $user_vouchersExists = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
		            throw new Zeed_Exception('该用户不存在或被冻结');
		        }
		        
		        /*判断用户是否有代金券*/
		        if (! $user_vouchers = Cas_Model_User_Voucher::instance()->fetchByWhere("userid = {$res['data']['userid']} and is_manager=0","creat_time desc")) {
		            throw new Zeed_Exception('该用户没有代金券');
		        }

		        if(!empty($user_vouchers)){
		            foreach ($user_vouchers as $k=>&$v){
		                if($v['voucher_status']==1){
		                    /*判断代金券是否过期*/
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
		        
		         // 分页
		        if(empty($res['data']['p'])) {
		            $res['data']['p'] = 1;
		        }
		        
		        /*默认每页显示数*/
		        if(empty($res['data']['psize'])) {
		            $res['data']['psize'] = self::$_perpage;
		        }
		         
		        $page = $res['data']['p'] - 1;
		        $offset = $page * $res['data']['psize'];
		        
		        /* 查询条件 */
		        $where = "userid = {$res['data']['userid']}";
		        
		        if($res['data']['voucher_status'] == 1){ //1 ：未使用 2：已使用 3 ：已过期
		            $where .= " AND voucher_status=1";
		        }elseif($res['data']['voucher_status'] == 2){
		            $where .= " AND voucher_status=2";
		        }elseif($res['data']['voucher_status'] == 3){
		            $where .= " AND voucher_status=3";
		        }

		        $order = array(
		            'is_manager desc',
		            'id desc',
		        );
		        
		        $voucher = Cas_Model_User_Voucher::instance()->fetchByWhere($where,$order,$res['data']['psize'],$offset);
		        //$a = Cas_Model_User_Voucher::instance()->getAdapter()->getProfiler()->getLastQueryProfile()->getQuery();
		        $count = Cas_Model_User_Voucher::instance()->getCount($where);
		        // 计算总页数
		        $pageCount = ceil($count / $res['data']['psize']);
		       
		        if(!empty($voucher)){
		            foreach ($voucher as $k=>&$v){
		                //字段处理
		                    if($v['voucher_money']>=10000){
		                        $v['content_money'] = ($v['voucher_money']/10000).'万元';
		                    }else{
		                        $v['content_money'] = $v['voucher_money'].'元';
		                    }
		                 //已使用时：
		                      if($v['is_manager']==1){
		                          if($v['voucher_status']==2){
		                              if($v['money_remarks']>=10000){
		                                  $v['content_money'] = ($v['money_remarks']/10000).'万元';
		                              }else{
		                                  $v['content_money'] = $v['money_remarks'].'元';
		                              }
		                          }
		                      }
		                    
		                if($v['type']==2){
		                    $v['remarks'] = '免费送收益';
		                    $v['right_title'] = '使 用';
		                    if($v['is_manager']==1){
		                        $v['left_title'] = '推广金';
		                        $v['remarks'] = '送佣金，每笔使用'.($v['use_money']/10000).'万元';
		                    }else{
		                        $v['left_title'] = '体验金';
		                        $v['remarks'] = '';
		                    }
		                }
		                 
		                if($v['type']==1){
		                    $v['right_title'] = '代金券';
		                    $v['remarks'] = '起投金额'.$v['use_money'].'元';
		                    $v['left_title'] = '';
		                }
		                 
		                if($v['type']==3){
		                    if(!empty($v['increase_interest'])){
		                        $v['content_money'] = '+'.($v['increase_interest']*100).'%';
		                    }
		                    $v['right_title'] = '加息券';
		                    $v['remarks'] = '可增加年化收益率'.($v['increase_interest']*100).'%';
		                    $v['left_title'] = '';
		                }
		                
		                $v['time'] = substr($v['start_data'], 0,10).'至'.substr($v['valid_data'],0,10);
		            }
		        }
		        
		        $res['data']['totalnum'] =$count; 
		        $res['data']['currentpage'] =(int)$res['data']['p'];
		        $res['data']['totalpage'] =$pageCount;
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
        if (! isset($params['voucher_status']) || ! $params['voucher_status']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '参数 voucher_status 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
