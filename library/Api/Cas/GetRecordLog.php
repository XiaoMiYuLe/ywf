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
 * 新增筛选条件 wuwb
 */

/**
 * 获取用户资金流水
 */
class Api_Cas_GetRecordLog
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
		        $where = "userid = {$res['data']['userid']}";
                        
                        /* 筛选条件*/
                        if ($res['data']['start_time']) {
                            $where .= " and ctime >= '{$res['data']['start_time']}'";
                        }
                        if ($res['data']['end_time']) {
                            $where .= " and ctime <= '{$res['data']['end_time']}'";
                        }
                        
                        // 安全考虑
                        if ($res['data']['status']) {
                            switch ($res['data']['status']){
                                case 1:
                                    $where .= " and type = {$res['data']['status']}";
                                    break;
                                case 2:
                                    $where .= " and type = {$res['data']['status']}";
                                    break;
                                case 3:
                                    $where .= " and type = {$res['data']['status']}";
                                    break;
                                case 4:
                                    $where .= " and type = {$res['data']['status']}";
                                    break;
                                case 5:
                                    $where .= " and type = {$res['data']['status']}";
                                    break;
                                default :
                                    break;
                            }
                        }
                        
		        $order = array(
		            'record_id DESC',
		        );
		        $record = Cas_Model_Record_Log::instance()->fetchByWhere($where , $order, $res['data']['psize'],$offset);
		        if(!empty($record)){
		            foreach ($record as $k=>&$v){
		                if($v['type'] == 1 ){
		                    $v['type'] = '充值';
		                }elseif($v['type'] == 2){
		                    $v['type'] = '提现';
		                }elseif($v['type'] == 3){
		                    $v['type'] = '账户购买';
		                }elseif($v['type'] == 4){
		                    $v['type'] = '收益分配';
		                }elseif($v['type'] == 5){
		                    $v['type'] = '佣金结算';
		                }elseif($v['type'] == 6){
		                    $v['type'] = '本金结算';
		                }elseif($v['type'] == 7){
		                    $v['type'] = '转让手续费';
		                }elseif($v['type'] == 8){
		                    $v['type'] = '转让结算';
		                }
		            }
		        }
		        
		        /*总记录数*/
		        $count = Cas_Model_Record_Log::instance()->getCount($where);
		         
		        // 计算总页数
		        $pageCount = ceil($count / $res['data']['psize']);
		        
		        $res['data']['totalnum'] = $count;//总记录数
		        $res['data']['currentpage'] = $res['data']['p'];//当前页
		        $res['data']['totalpage'] = $pageCount;//总页数
		        
		        
		        $res['data']['content']= $record ? $record : array();
		        unset($res['data']['p']);
		        unset($res['data']['psize']);
		        unset($res['data']['userid']);
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取用户资金流水失败。错误信息：' . $e->getMessage();
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
