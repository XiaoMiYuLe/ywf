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
 * 获取广告所属页面 -- 用户前端查看
 * @todo 读取表 advert_pages
 */
class Api_Advert_GetPages
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                $redis_set = Trend_Model_Setting::instance()->fetchByFV('name', 'is_open_redis','val');
                $redis_val = empty($redis_set) ? 0 : $redis_set[0]['val'];
                
                $cache_key = md5(json_encode($res['data']));
                $data      = $redis_val == 1 ? Trend_Model_Redis::instance()->get($cache_key) : false;
                if (!$data) {
                    // 只是提供商端查看，无条件和排序
                    $where = null;
                    $order = null;
                    $data = Advert_Model_Page::instance()->fetchByWhere($where, $order);
                    $data = json_encode($data);
                    if ($redis_val == 1) {Trend_Model_Redis::instance()->set($cache_key, $data);}
                }
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '查询广告所属页面出错。错误信息：' . $e->getMessage();
                return $res;
            }
            
            if (! empty($data)) {
            	$res['data'] = json_decode($data,true);
            } else {
            	$res['data'] = array();
            }
        }
        
        return $res;
    }
    
    public static function validate($params)
    {
        ksort($params);
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
