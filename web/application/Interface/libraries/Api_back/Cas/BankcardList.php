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
 * 银行列表
 */
class Api_Cas_BankcardList
{
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');
    protected static $_allowFields = array();
    public static function run($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
        	try {
                $bankList = Cas_Model_BankConfig::instance()->fetchByWhere("channel_id = {$params['channel']}");
                if(!empty($bankList)){
                    foreach($bankList as &$v){
                        $v['info'] = self::getInfo($v);
                    }
                }
                $res['data'] = $bankList;
          //       print_R($bankList);exit;
        		// //配置文件
        		// $url = Zeed_Config::loadGroup('urlmapping');
        		// //银行名称数组
        		// $res['data']['bank_name'] = $url['bank_name'];
        	 //    //银行限额数组
        		// $res['data']['bank_quota'] = $url['bank_quota'];
		       
        	} catch (Zeed_Exception $e) {
        		$res['status'] = 1;
        		$res['error']  = '错误信息：' . $e->getMessage();
        		return $res;
        	}
        }
        return $res;
    }
    
    /**
     * 验证参数
     */
    public static function validate($params)
    {
        /* 组织数据 */
        $set = array();
        foreach (self::$_allowFields as $f) {
            $set[$f] = isset($params[$f]) ? $params[$f] : null;
        }
        self::$_res['data'] = $set;
        
        return self::$_res;
    }

    /**
     * 拼接字符串
     * @return [type] [description]
     */
    public static function getInfo($data){
        $one_time = ($data['one_time']/10000) >=1?($data['one_time']/10000).'万':$data['one_time'];
        $one_day = ($data['one_day']/10000) >=1?($data['one_day']/10000).'万':$data['one_day'];
        $one_month = $data['one_day'] > 0 ?($data['one_day']/10000).'万':'无限额';
        return "单笔{$one_time},单日{$one_day},单月{$one_month}";
    }
}

// End ^ Native EOL ^ encoding
