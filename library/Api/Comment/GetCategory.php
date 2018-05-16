<?php

/**
 * 获取评论分类
 *
 * @author hudongsheng <396256087@qq.com>
 */
class Api_Comment_GetCategory
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
                	$order = "hid ASC";
                	$data = Comment_Model_Category::instance()->fetchByWhere($where, $order);
                	$data = json_encode($data);
                	if ($redis_set == 1) {
                	    Trend_Model_Redis::instance()->set($cache_key, $data);
                	}
                }
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '获取评论分类出错。错误信息：' . $e->getMessage();
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
