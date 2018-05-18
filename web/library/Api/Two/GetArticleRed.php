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
 * 获取最新文章
 */
class Api_Two_GetArticleRed
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        	    /* 检查用户是否存在 */
        	    if($res['data']['userid']){//登录
            	    if (! $userExists = Cas_Model_User::instance()->fetchByWhere("userid = {$res['data']['userid']} and status = 0")) {
            	        throw new Zeed_Exception('该用户不存在或被冻结');
            	    }
            	    $order = 'ctime DESC';
            	    $article = Article_Model_Content::instance()->fetchByWhere('status=1 and category=9',$order,1,0);
            	    
            	    $userExists[0]['article_read_time'] = strtotime($userExists[0]['article_read_time']);
            	    $article[0]['ctime'] = strtotime($article[0]['ctime']);
            	    if($userExists[0]['article_read_time']<$article[0]['ctime']){
            	        $result = 1; //有新文章
            	    }else{
            	        $result = 2; //没有新文章
            	    }
        	    }else{//没有登录
        	        $result = 2; //没有新文章
        	    }
        	    
		        $res['data'] = $result ? $result : array();
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '获取新消息失败。错误信息：' . $e->getMessage();
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
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
