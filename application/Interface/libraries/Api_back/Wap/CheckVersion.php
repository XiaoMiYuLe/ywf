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
 * 检查 APP 的最新版本
 */
class Api_Wap_CheckVersion
{

    /**
     * 返回参数
     */
    protected static $_res = array(
            'status' => 0,
            'error' => '',
            'data' => ''
    );

    /**
     * 接口运行方法
     *
     * @param string $params            
     * @throws Zeed_Exception
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
    public static function run ($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                
                /* 条件设定 */
                $where = "status = 1 AND platform = '{$res['data']['platform']}'";
                $order = 'ctime DESC';
                $cols = array(
                        'id',
                        'platform',
                        'v_code',
                        'v_name',
                        'content',
                        'filepath',
                        'size',
                        'ctime'
                );
                
                $versions = Trend_Model_Version::instance()->fetchByWhere($where, $order, null, null, $cols);
                
                if (empty($versions)) {
                    throw new Zeed_Exception('查无该平台的版本信息');
                }
                
                /* 比较 */
                $version = $versions[0];
                if ($version['v_code'] <= $res['data']['v_code_now']) {
                    throw new Zeed_Exception('当前已是最新版本，无需更新');
                }
                
                $res['data'] = $version;
            } catch (Exception $e) {
                $res['status'] = 1;
                $res['error'] = '检查版本失败。错误信息：' . $e->getMessage();
                return $res;
            }
        }
        
        return self::$_res = $res;
    }

    /**
     * 验证方法
     * 
     * @param unknown $params            
     * @return multitype:number string
     */
    public static function validate ($params)
    {
        if (! isset($params['platform']) || ! $params['platform']) {
            self::$_res['status'] = 1;
            self::$_res['msg'] = '参数 platform 未提供';
            return self::$_res;
        }
        
        if (! isset($params['v_code_now']) || ! $params['v_code_now']) {
            self::$_res['status'] = 1;
            self::$_res['msg'] = '参数 v_code_now 未提供';
            return self::$_res;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
