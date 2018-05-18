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
 * 获取版本信息
 */
class Api_Trend_GetVersion
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    /**
     * 接口运行方法
     *
     * @param array $params
     * @return array
     */
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                $versions = Trend_Model_Version::instance()->fetchByWhere("id = 1");
                $res['data']['code'] = str_replace(".","",$res['data']['code']);
                if($res['data']['status'] == 1 && $res['data']['code'] < str_replace(".","",$versions[0]['web_code'])){
                    $cols = array('web_code','status','guide_url');/* 服务端  */
                    $version = Trend_Model_Version::instance()->fetchByWhere("id = 1",null,null,null, $cols);
                }elseif($res['data']['status'] == 2 && $res['data']['code'] < str_replace(".","",$versions[0]['ios_code'])){
                    $cols = array('ios_code','status','guide_url');/* ios端  */
                    $version = Trend_Model_Version::instance()->fetchByWhere("id = 1",null,null,null, $cols);
                }elseif($res['data']['status'] == 3 && $res['data']['code'] < str_replace(".","",$versions[0]['android_code'])){
                    $cols = array('android_code','status','guide_url');/* android端  */
                    $version = Trend_Model_Version::instance()->fetchByWhere("id = 1",null,null,null, $cols);
                }
                
                $data = $version ? $version : array();
                
                $res['data'] = $data;
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '获取版本信息失败。错误信息：' . $e->getMessage();
            }
        }
        return $res;
    }
    
    /**
     * 验证方法
     * 
     * @param array $params
     * @return array
     */
    public static function validate($params)
    {
        
        /* 校验必填项 */
        if (! isset($params['code']) || ! $params['code']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '版本号未提供';
            return self::$_res;
        }
       
        if (! isset($params['status']) || ! $params['status']) {
            self::$_res['status'] = 1;
            self::$_res['error']  = '状态未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
