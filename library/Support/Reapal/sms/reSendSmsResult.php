<?php
/**
 * 确认支付接口
 */

class Support_Reapal_sms_reSendSmsResult
{
	/**
	 * 确认支付接口
	 *
	 * @param string $data 请求数据
	 * @return data
	 */
	protected static $_res = array('status' => 0, 'data' => '', 'error' => null);
	
	public static function run ($data)
	{
		if (! $data) {
			return false;
		}
		require_once dirname(dirname(__FILE__)).'/util.php'; 
		require_once dirname(dirname(__FILE__)).'/config.php';   

		//参数数组
		$paramArr = array(
		     'merchant_id' => $data['merchant_id'],
		     'order_no' => $data['order_no'],
		     'version' => '3.1.2'
		);
		
		//访问储蓄卡签约服务
		$url = $apiUrl.'/fast/sms';
// 		echo $url,"\n";
		
		$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id);
		
		
		$response = json_decode($result,true);
		$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
		$data = AESDecryptResponse($encryptkey,$response['data']);
		
		//json解析
		$data = json_decode($data);
		self::$_res['data'] = $data;
		self::$_res['status'] = $data->result_code;//返回码
		self::$_res['error'] = $data->result_msg;//错误信息
		return self::$_res;
	}
}