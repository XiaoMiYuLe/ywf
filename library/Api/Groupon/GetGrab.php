<?php

/**
 * 获取抢购
 *
 * @author hudongsheng <396256087@qq.com>
 */
class Api_Groupon_GetGrab
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                
                $where = "1 = 1 ";
                if ( $res['data']['category_id'] ) {
                    $where .= " AND FIND_IN_SET({$res['data']['category_id']},category_id)";
                }
                /* 未开始 */
                if($res['data']['type'] == 1) {
                    $where .= " AND start_time > now()";
                }
                /* 开始 */
                if($res['data']['type'] == 2) {
                    $where .= " AND start_time < now() AND end_time > now()";
                }
                
                /* 结束 */
                if($res['data']['type'] == 3) {
                    $where .= " AND end_time < now()";
                }
                $categorys = Groupon_Model_Grab::instance()->fetchByWhere($where, "ctime DESC");
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
