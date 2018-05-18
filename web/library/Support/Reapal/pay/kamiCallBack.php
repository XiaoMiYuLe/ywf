<?php
/**
 * kami回调处理
 */

class Support_Reapal_pay_kamiCallBack
{
	/**
	 * 确认支付接口
	 *
	 * @param string $data 请求数据
	 * @return data
	 */
	protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
	
	public static function run($data,$encryptkey,$merchant_id)
	{
		if (! $data) {
			return false;
		}
		require_once dirname(dirname(__FILE__)).'/util.php'; 
		require_once dirname(dirname(__FILE__)).'/config.php';
		   
	$encryptkey = RSADecryptkey($encryptkey,$merchantPrivateKey);
	$decryData = AESDecryptResponse($encryptkey, $data);

	$jsonObject = json_decode($decryData,true);
	
	$merchant_id = $jsonObject['merchant_id'];
	$trade_no = $jsonObject['trade_no'];
	$order_no = $jsonObject['order_no'];
	$bind_id = $jsonObject['bind_id'];
	$result_code = $jsonObject['result_code'];
	$result_msg = $jsonObject['result_msg'];
	$set['result_code'] = $result_code;
	$set['trade_no'] = $trade_no;
	$set['order_no'] = $order_no;
	$set['bind_id'] = $bind_id;
	$set['ctime'] = date(DATETIME_FORMAT);
// 	$set['result_msg'] = $decryData;//返回码
	$set['result_msg'] = $result_msg;//返回码
	Cas_Model_Kami::instance()->insert($set);
// 	$paramarr = array(
// 		'merchant_id' => $merchant_id,
// 		'trade_no' => $trade_no,
// 		'order_no' => $order_no,
// 		'total_fee' => $total_fee,
// 		'status' => $status,
// 		'result_code' => $result_code,
// 		'result_msg' => $result_msg,
// 		'notify_id' => $notify_id
// 	);
	
		if($result_code == "0000"){
			//成功
// 			$set['news_title'] = $order_no;
// 			$set['ctime'] = date(DATETIME_FORMAT);
// 			$set['news_content'] = $status;//返回码
// 			News_Model_List::instance()->insert($set);
			$verifyStatus = "success";
		}else {
			//失败
			$verifyStatus = "fail";
		}
		
	    echo $verifyStatus;
	    return $verifyStatus;
		
	}
}