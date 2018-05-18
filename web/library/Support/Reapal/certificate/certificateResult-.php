<?php
/**
 * 储蓄卡签约
 */

class Support_Reapal_certificate_certificateResult
{
	/**
	 * 卡密鉴权接口
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
        'merchant_id' => $data['merchant_id'],   //商户在融宝的账户ID
        "member_id" => $data['member_id'],
        "bind_id" => $data['bind_id'],
        'order_no' => $data['order_no'],                //商户生成的唯一订单号
        // "order_no" => $order_no,  //"rbpay_app2016022611442885503",
        "return_url" => $data['return_url'],  
        "notify_url" => "http://140.207.46.14:20033/CallBack",
        "terminal_type" => "mobile",
        'version' => '3.1.3'                   //版本控制默认3.0
   );

//访问储蓄卡签约服务
$url = $apiUrl.'/fast/certificate';
// echo $url,"\n";

$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id);
// echo $result;

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
/*$response = json_decode($result,true);
$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
echo $encryptkey,"\n";
echo AESDecryptResponse($encryptkey,$response['data']);*/
	}
}