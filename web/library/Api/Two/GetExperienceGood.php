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
class Api_Two_GetExperienceGood
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
	/* 根据用户手机号登录 */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] === 0) {
            try {
                if(!($data = Goods_Model_List::instance()->fetchByWhere("goods_pattern=4 and is_manager=0"))){
                    throw new Zeed_Exception('查无此产品');
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
        self::$_res['data'] = $params;
        
        return self::$_res;
    }
}