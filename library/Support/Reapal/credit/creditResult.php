<?php
/**
 * 信用卡签约
 */

class Support_Reapal_credit_creditResult
{
	/**
	 * 执行切割
	 *
	 * @param string $img 完整路径的图片
	 * @param string $save_path 要保存的图片地址
	 * @return boolean
	 */
	public static function run ($data)
	{
		if (! $data) {
			return false;
		}

		header("Content-Type:text/html;charset=UTF-8");
		require_once '../util.php'; 
		require_once '../config.php'; 
		
		//参数数组
		$paramArr = array(
		     'merchant_id' => $data['merchant_id'],
		     'card_no' => $data['card_no'],
		     'owner' => $data['owner'],
		     'cert_type' => '01',
		     'cert_no' => $data['cert_no'],
		     'phone'=> $data['phone'], 
			 'cvv2' => $data['cvv2'],
		     'validthru' => $data['validthru'],
		     'order_no' =>'12312312312323124',
		     'transtime' => '123456',
		     'currency' => '156',
		     'total_fee' => $data['total_fee'],
		     'title' => 'yyyyy',
		     'body' => 'yyyy',
		     'member_id' => $data['member_id'],
		     'terminal_type'=>'mobile',
		     'terminal_info' => '554545',
		     'member_ip' => '120.55.213.139',
		     'seller_email' => '820061154@qq.com',
			 'notify_url' => 'www.12345.com',
		     'token_id' => '1234568779',
		     'version' => '3.1.2'	
		);
		
		//访问储蓄卡签约服务
		$url = $apiUrl.'/fast/credit/portal';
// 		echo $url,"\n";
		
		$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id);
		
		
		$response = json_decode($result,true);
		$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
// 		echo $encryptkey,"\n";
		return AESDecryptResponse($encryptkey,$response['data']);
	}
}
