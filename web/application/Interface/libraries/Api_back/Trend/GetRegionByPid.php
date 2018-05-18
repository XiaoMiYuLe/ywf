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
 * 获取指定地区的子孙级地区
 */
class Api_Trend_GetRegionByPid
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
                $cols = array('region_id', 'pid', 'region_name');
                $regions = Trend_Model_Region::instance()->fetchByFV('pid', $res['data']['pid'], $cols);
                $data = $regions ? $regions : '';
                
                $res['data'] = $data;
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '查询地区信息失败。错误信息：' . $e->getMessage();
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
        if (! isset($params['pid']) || ! $params['pid']) {
            $params['pid'] = 0;
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
