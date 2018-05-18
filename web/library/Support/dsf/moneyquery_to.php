<?php
header("Content-Type:text/html;charset=utf-8");
require_once 'util.php'; 
require_once 'config.php'; 

//参数数组
$paramArr = array(
     'charset' => $charset,
	 'sign_type' => $sign_type,
   );
   
$url = $dsfUrl.'agentpay/balancequery';
echo $url,"\n";
$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id,$version);
print_r($result);
$response = json_decode($result,true);
print_r($response);
$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
echo AESDecryptResponse($encryptkey,$response['data']);
?>