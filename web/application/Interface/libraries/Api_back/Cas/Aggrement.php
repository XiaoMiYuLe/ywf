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
class Api_Cas_Aggrement
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
    protected static $_allowFields = array();
	/* 根据用户手机号登录 */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] === 0) {
            try {
                
                $url = Zeed_Config::loadGroup('urlmapping');
                $login_url = $url['store_url_login'].'/cas/word/agreement/';
                $res['data']['url'] = $login_url;
                if(empty($res['data'])){
                    $res['data'] = array();
                }
            } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = "错误信息：" . $e->getMessage();
                return $res;
            }
        }
        
        return $res;
    }
    
    
    /**
     * 验证参数
     */
    public static function validate ($params)
    {
        /* 组织数据 */
        $set = array();
        foreach (self::$_allowFields as $f) {
            $set[$f] = isset($params[$f]) ? $params[$f] : null;
        }
        self::$_res['data'] = $set;
        
        return self::$_res;
    }
     /*
     * 加密算法
     */
    public static function encrypt($str='',$salt='')
    {
        return MD5(MD5($str).$salt);
    }
}