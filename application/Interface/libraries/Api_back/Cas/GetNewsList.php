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
 * 获取消息列表
 */
class Api_Cas_GetNewsList
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
        	    $order = 'ctime DESC';
		        $news = News_Model_List::instance()->fetchByWhere('1=1',$order,null,null);
		        
		        if(!empty($news)){
		            $arr = array(
		                    'read_time' => date(DATETIME_FORMAT),
		            );
		            Cas_Model_User::instance()->update($arr, "userid={$res['data']['userid']}");
		        }
		        $res['data'] = $news ? $news : array();
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取消息列表失败。错误信息：' . $e->getMessage();
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
