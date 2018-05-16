<?php
/**
 * 绑卡签约接口
 */

class Support_Reapal_bindcardportal_bindCardResult
{
	/**
	 * 绑卡签约接口
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
		     'bind_id' => $data['bind_id'],
		     'order_no' =>$data['order_no'],
		     'transtime' => time(),
		     'currency' => '156',
		     'title' => 'yyyyy',
		     'body' => 'yyyy',
		     'member_id' => $data['member_id'],
		     'terminal_type'=>'mobile',
		     'terminal_info' => '554545',
		     'member_ip' => '192.168.1.83',
		     'seller_email' => '820061154@qq.com',
			 'cvv2' => '341',
			 'validthru' => '1215',
			 'total_fee' => $data['total_fee']
		);
		
		//访问储蓄卡签约服务
		$url = $apiUrl.'/fast/bindcard/portal';
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