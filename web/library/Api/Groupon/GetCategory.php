<?php

/**
 * 获取团购和抢购标签
 *
 * @author hudongsheng <396256087@qq.com>
 */
class Api_Groupon_GetCategory
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                
                $where = $res['data']['parent_id'] ? "parent_id = {$res['data']['parent_id']}" : "parent_id = 0";
                $categorys = Groupon_Model_Category::instance()->fetchByWhere($where, "sort_order DESC");
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error'] = '获取出错。错误信息：' . $e->getMessage();
                return $res;
            }
            
            if (! empty($categorys)) {
            	$res['data'] = $categorys;
            } else {
            	$res['data'] = array();
            }
        }
        
        return $res;
    }
    
    // 参数验证
    public static function validate($params)
    {
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}
