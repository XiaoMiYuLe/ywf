<?php
/**
 * 储蓄卡签约
 */

class Support_Reapal_debit_debitResult
{
	/**
	 * 执行储蓄卡签约
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
		$config = Zeed_Config::loadGroup('urlmapping');
		$ip = Zeed_Util::clientIP();
		//参数数组
		$paramArr = array(
		     'merchant_id' => $data['merchant_id'],
		     'card_no' => $data['card_no'],
		     'owner' => $data['owner'],
		     'cert_type' => '01',
		     'cert_no' => $data['cert_no'],
		     'phone'=> $data['phone'],
		     'order_no' =>'bk' . time().rand(1,9999),
		     'transtime' => date("Y-m-d H:i:s"),
		     'currency' => '156',
		     'total_fee' => $data['total_fee'],
		     'title' => 'yyyyy',
		     'body' => 'yyyy',
		     'member_id' => $data['member_id'],
		     'terminal_type'=>'mobile',
		     'terminal_info' => '554545',
		     'member_ip' => $ip,
		     'seller_email' => '820061154@qq.com',
		     'notify_url' => $config['callback_url']."/CallBack",
			 'token_id' => '1234567890765463',
		     'version' => '3.1.3'
		
		);
		
		//访问储蓄卡签约服务
		$url = $apiUrl.'/fast/debit/portal';
// 		echo $url,"\n";
		
		$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id);
		
		
		$response = json_decode($result,true);
		$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
// 		echo $encryptkey,"\n";
		$data = AESDecryptResponse($encryptkey,$response['data']);
		
		//json解析
		$data = json_decode($data);
		self::$_res['data'] = $data;
		self::$_res['status'] = $data->result_code;//返回码
		self::$_res['error'] = $data->result_msg;//错误信息
		return self::$_res;
		
	}
}
