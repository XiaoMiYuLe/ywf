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
 * 退货
 * 针对商品进行退货
 * 只要已发货，就可以申请退货
 */
class Api_Bts_Order_Refund
{
    /**
     * 返回参数
     */
    protected static $_res = array('status' => 0, 'error' => '', 'data' => '');

    /**
     * 接口运行方法
     * 
     * @param string $params
     * @throws Zeed_Exception
     * @return string|Ambigous <string, multitype:number, multitype:number string , unknown, multitype:>
     */
    public static function run ($params = null)
    {
        $res = self::validate($params);
        if ($res['status'] == 0) {
            try {
                
            	$fileUpload = $res['data']['_fileUpload_'];
                /* 检查用户是否存在 */
            	$userExists = Cas_Model_User::instance()->fetchByPK($res['data']['userid']);
        		if (! $userExists) {
        		    throw new Zeed_Exception('该用户不存在');
        		}
        		$userExists = current($userExists);
        		$items = Bts_Model_Order_Items::instance()->fetchByPk($res['data']['item_id']);
        		if (! $items) {
        			throw new Zeed_Exception('退货详情不存在,请勿非法操作');
        		}
        		$items = $items[0];
        		
        		// 判断退货单状态
        		$status = 2 ;
        		
        		$refund_sn = Bts_Model_Order_Refund::instance()->getSimpleReturnNumberToken();
        		
        		$orderinfo = Bts_Model_Order::instance()->fetchByPK($items['order_id'], array('order_number'));
				/* 组织退货数据 */
        		$set = array(
        		    'order_id'  => $items['order_id'],
        		    'refund_sn' => $refund_sn,
        		    'item_id' => $items['item_id'],
        		    'order_number' => $orderinfo[0]['order_number'],
        		    'price' => $items['buy_price'],
        		    'reason' => $res['data']['reason'],
        		    'operator_type' => 'cas',
        		    'operator_userid' => $res['data']['userid'],
        		    'ctime' => date(DATETIME_FORMAT),
        		    'status' => $status
        		);
        		
        		$returnId = Bts_Model_Order_Refund::instance()->addForEntity($set);
        		
		        // 退货信息
		        $res['data'] = $set;
		        
		        // 图片上传处理
		        if (! empty($fileUpload)) {
		        	foreach ($fileUpload as $value) {
		        		Bts_Model_Order_Attachment::instance()->addForEntity(array('to_id' => $returnId, 'attachmentid' => $value, 'userid' => $res['data']['userid'] ,'type' =>'order_return'));
		        	}
		        }
		        
		        /* 记录日志@todo */
            } catch (Zeed_Exception $e) {
                $res['status'] = 1;
                $res['error']  = '申请退款失败。错误信息： ' . $e->getMessage();
                return $res;
            }
        }
        return $res;
    }
    
    /**
     * 数据校验
     * 
     * @param unknown $params
     * @return multitype:number string
     */
    public static function validate ($params)
    {
        /**
         *  校验参数
         */
        if (! $params['token'] || ! Cas_Token::isTokenTime($params['token'])) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数 token未提供或无效的token';
            return self::$_res;
        }
        $params['userid'] = Cas_Token::getUserIdByToken($params['token']);
        
        if (! $params['order_number']) {
            self::$_res['status'] = 1;
            self::$_res['error'] = '参数订单编号 order_number 未提供';
            return self::$_res;
        }
        
        if (! $params['item_id']) {
        	self::$_res['status'] = 1;
        	self::$_res['error'] = '参数订单物品ID item_id 未提供';
        	return self::$_res;
        }
        
         if (! $params['reason']) {
        	self::$_res['status'] = 1;
        	self::$_res['error'] = '退货原因 reason 未提供';
        	return self::$_res;
        }        
        
        // 上传文件
        $params['_fileUpload_'] = array();
        if (isset($_FILES['image1']) && $_FILES['image1']['error'] == UPLOAD_ERR_OK) {
        	$attachment = Trend_Attachment::add($_FILES['image1']['tmp_name'], NULL, $params['userid']);
        	$params['_fileUpload_'][] = $attachment['attachmentid'];
        }
        if (isset($_FILES['image2']) && $_FILES['image2']['error'] == UPLOAD_ERR_OK) {
        	$attachment = Trend_Attachment::add($_FILES['image2']['tmp_name'], NULL, $params['userid']);
        	$params['_fileUpload_'][] = $attachment['attachmentid'];
        }
        if (isset($_FILES['image3']) && $_FILES['image3']['error'] == UPLOAD_ERR_OK) {
        	$attachment = Trend_Attachment::add($_FILES['image3']['tmp_name'], NULL, $params['userid']);
        	$params['_fileUpload_'][] = $attachment['attachmentid'];
        }
        
        self::$_res['data'] = $params;
        return self::$_res;
    }
}

// End ^ Native EOL ^ encoding
