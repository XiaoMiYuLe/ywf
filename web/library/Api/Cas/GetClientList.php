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
 * 16.9.20新增了一个筛选条件-万保
 */

/**
 * 获取用户客户列表
 */
class Api_Cas_GetClientList
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_perpage = 20;
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		/* 检查用户是否存在 */
		        if (! $userExists = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
		            throw new Zeed_Exception('该用户不存在或被冻结');
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
		        $where = "parent_id = {$res['data']['userid']}";
                        
                        /* 客户状态 1-已加入 2-未加入*/
                        $status = $res['data']['status'];
                        if ($status == 1) { //已加入 is_invitaiton=5
                            $where .= " and is_invitaiton = 5";
                        }
                        if ($status == 2) {
                            $where .= " and is_invitaiton !=5";
                        }
                        
		        $order = "ctime DESC";
		        $cols = array(
		                'username',
		                'phone',
		                'ctime',
		                'is_ecoman',
		                'userid',
		                'bank_id',
		                'is_invitaiton',//申请状态 0：未被邀请 1：已经被邀请 2：审核中 3：重新上传名片 4：审核未通过
		        );
		        $client = Cas_Model_User::instance()->fetchByWhere($where,$order,$res['data']['psize'],$offset, $cols);
		        if(!empty($client)){
		            foreach($client as $k=>&$v){
		            	$v['username'] = $v['username']? $v['username'] :'无';
		                $v['ctime'] = strtotime($v['ctime']);
		                $v['ctime'] = date("Y-m-d",$v['ctime']);
		                //绑卡的状态
		                if(!empty($v['bank_id'])){
		                    $bank_info = Cas_Model_Bank::instance()->fetchByWhere("bank_id='{$v['bank_id']}' and is_use=1 and is_del=0");
		                    if(!empty($bank_info)){
		                        $v['is_tiecard'] = 1;
		                    }else{
		                        $v['is_tiecard'] = 0;
		                    }
		                }else{
		                    $v['is_tiecard'] = 0;
		                }
		                unset($v['bank_id']);
		            }
		        }
		        

		        /*总记录数*/
		        $count = Cas_Model_User::instance()->getCount($where);
		         
		        // 计算总页数
		        $pageCount = ceil($count / $res['data']['psize']);
		        
		        $res['data']['totalnum'] = $count;//总记录数
		        $res['data']['currentpage'] = $res['data']['p'];//当前页
		        $res['data']['totalpage'] = $pageCount;//总页数
		        /* 处理有效时间  */
		        $res['data']['content'] = $client ? $client : array();
		        unset($res['data']['p']);
		        unset($res['data']['psize']);
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取用户客户列表失败。错误信息：' . $e->getMessage();
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
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
