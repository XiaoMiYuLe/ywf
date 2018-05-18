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
 * 银行列表
 */
class Api_Cas_BankfuyoucardList
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_allowFields = array();
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
        		//配置文件
        		$url = Zeed_Config::loadGroup('urlmapping');
        		//银行名称数组
        		$res['data']['bank_name'] = $url['fuyoubank_name'];
        	    //银行限额数组
        		$res['data']['bank_quota'] = $url['fuyoubank_quota'];
		       
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '错误信息：' . $e->getMessage();
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
