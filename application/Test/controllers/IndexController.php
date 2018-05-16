<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */

class IndexController extends IndexAbstract
{
    /**
     * test 首页
     * 741
     */
    public function index()
    {
        echo 'success';
        echo "<script>window.android.onClickAndroid();</script>";
        exit();
        $p = array();
        $n = 60;
        $listing = Interface_Model_Listing::instance()->fetchByFV('project_id', 4);
        foreach ($listing as $k => $v) {
            $api_id_new = $n + $k;
            
            $where = "api_id = {$v['id']}";
            $order = "id ASC";
            $params = Interface_Model_Params::instance()->fetchByWhere($where, $order);
            foreach ($params as $kk => $vv) {
                unset($vv['id']);
                $vv['api_id'] = $api_id_new;
                
//                 Helper_Api::run($vv);
                $p[] = $vv;
            }
        }
        
        $p_json = json_encode($p);
        Interface_Model_Group::instance()->updateForEntity(array('content' => $p_json), 48);
        
//         Zeed_Benchmark::print_r($p);
    }
    
    public function test()
    {
        $arr = array('status' => 0, 'msg' => 'ok', 'data' => '123');
        echo json_encode($arr);exit;
    }
}

// End ^ Native EOL ^ UTF-8