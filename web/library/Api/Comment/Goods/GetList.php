<?php

/**
 * 获取评论
 *
 * @author hudongsheng <396256087@qq.com>
 */
class Api_Comment_Goods_GetList
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
                	// 条件和排序
                	$where = " to_id = {$res['data']['to_id']} AND category_id = {$res['data']['category_id']}  AND is_del = 0";
                	$order = "ctime ASC";
                	$data = Comment_Model_Content::instance()->fetchByWhere($where, $order);
                	$data = json_encode($data);
                    if ($redis_val == 1){ 
                        Trend_Model_Redis::instance()->set($cache_key, $data);
                    }
                }
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '获取评论出错。错误信息：' . $e->getMessage();
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
    
    // 参数验证
    public static function validate($params)
    {
        ksort($params);
    	if (! intval( $params['category_id'] )) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 category_id未提供';
            return self::$_res;
        }
        
    	if (! intval( $params['to_id'] )) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 to_id未提供';
            return self::$_res;
        }
        self::$_res['data'] = $params;
        return self::$_res;
    }
}
