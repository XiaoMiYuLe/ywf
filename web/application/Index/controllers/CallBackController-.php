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
class CallBackController extends IndexAbstract 
{
	/**
     * 默认返回数据
     */
	protected static $_res = array('status' => 0, 'msg' => '', 'data' => '');
	  
    public  function index($params = null)
    {
    	$this->addResult(self::RS_SUCCESS, 'json');
    	/* 验证是否有数据  */
    	$data = $this->input->post('data', 'data');
    	$encryptkey = $this->input->post('encryptkey', 'en');
    	$merchant_id = $this->input->post('merchant_id', 'mec');
//     	$data = $_REQUEST['data'];
//     	$encryptkey = $_REQUEST['encryptkey'];
//     	$merchant_id = $_REQUEST['merchant_id'];
//     	$r['news_title'] = $data;
// 		$r['news_content'] = $encryptkey;
//     	News_Model_List::instance()->insert($r);
		if ($res['status'] == 0) {
			try {
			    $set = Support_Reapal_pay_CallBack::run($data,$encryptkey,$merchant_id);
// 			    if ($set['status'] != 0000) {
// 			    	throw new Zeed_Exception($set['error']);
// 			    }
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
// 		echo success;
// 		return success;
	}
	
}