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
class Api_Article_GetShare
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array('userid');
	/* 根据用户手机号登录 */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] === 0) {
            try {
                if($data = Article_Model_Content::instance()->fetchByPK($res['data']['content_id'],array('content_id','link','title','image','alias'))){
                    //对链接进行处理
        	    }else{
        	        throw new Zeed_Exception('查无此文章');
        	    }
        	    
        	    // 图片路径组合
        	    if (! empty($data)) {
        	        foreach ($data as $k => &$v) {
        	            $v['image'] = Support_Image_Url::getImageUrl($v['image']);
        	        }
        	    }
            } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = "错误信息：" . $e->getMessage();
                return $res;
            }
            
            if (! empty($data)) {
            	    $res['data'] = $data;
        	} else {
        	    $res['data'] = array();
        	}
        }
        
        return $res;
    }
    
    
    /**
     * 验证参数
     */
    public static function validate ($params)
    {
        if (! isset($params['content_id']) || $params['content_id'] < 1) {
        		self::$_res['status'] = 1;
        		self::$_res['error']  = '参数 content_id 未提供或格式错误';
        		return self::$_res;
        	}
        self::$_res['data'] = $params;
        
        return self::$_res;
    }
}