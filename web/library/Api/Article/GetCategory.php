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


class Api_Article_GetCategory
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    /**
     * 允许输出的字段
     */
    protected static $_allowFields = array('category_id', 'title');
    
    /**
     * 接口运行方法
     *
     * @param string $params
     * @throws Zeed_Exception
     * @return string Ambigous multitype:number, multitype:number string ,
     *         unknown, multitype:>
     */
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
                    $order = "sort_order DESC";
                    $offset = null;
                    $parent_id = 0;
                    
                    if($res['data']['parent_id']) {
                    $parent_id = $res['data']['parent_id'];
                    }
                    $where = "parent_id = {$parent_id}";
                    $data = Article_Model_Category::instance()->fetchByWhere($where, $order, $count, $offset, self::$_allowFields);
                    
                    $data = json_encode($data);
                    if ($redis_val == 1){ Trend_Model_Redis::instance()->set($cache_key, $data);}
                }
                self::$_res['data'] = json_decode($data);
                return self::$_res;
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '查询文章分类失败。错误信息：' . $e->getMessage();
                return $res;
            }
        }
        
        return $res;
    }
    
    /**
     * 验证方法
     * @param unknown $params
     * @return multitype:number string
     */
    public static function validate($params)
    {
        ksort($params);
    	self::$_res['data'] = $params;
        return self::$_res;
    }
}
// End ^ Native EOL ^ encoding
