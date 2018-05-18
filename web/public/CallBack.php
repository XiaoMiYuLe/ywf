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
 *支付接口（绑卡）
 * @author Administrator
 *
 */
// class CallBack
// {
	
// 	/**
//      * 默认返回数据
//      */
// 	protected static $_res = array('status' => 0, 'msg' => '', 'data' => '');
	  
//     public static function run($params = null)
//     {
        /* 验证是否有数据  */
    	$data = $this->input->get('data', 'data');
    	$encryptkey = $this->input->get('encryptkey', 'en');
    	$merchant_id = $this->input->get('merchant_id', 'mec');
    	$r['news_title'] = $data;
		$r['news_content'] = $encryptkey;
		var_dump($r);
    	News_Model_List::instance()->insert($r);
    	echo News_Model_List::instance()->getAdapter()->getProfiler()->getLastQueryProfile()->getQuery();die;
		if ($res['status'] == 0) {
			try {
			    $set = Support_Reapal_pay_CallBack::run($data,$encryptkey,$merchant_id);
			    if ($set['status'] != 0000) {
			    	throw new Zeed_Exception($set['error']);
			    }
			    $res['data'] = $set['data'];
			    $res['error'] = $set['error'];
			    
            /* 返回错误信息  */
			} catch(Zeed_Exception $e) {
				$res['status'] = 1;
				$res['error'] = '支付失败。错误信息：' . $e->getMessage();
				return $res;
			}
		}
		/* 返回数据 */
		return success;
// 	}
	
// }