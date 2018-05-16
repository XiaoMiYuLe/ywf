<?php
header("Content-Type:text/html;charset=utf-8");
require_once 'util.php'; 
require_once 'config.php'; 

//参数数组
$paramArr = array(
     'charset' => $charset,
	 'notify_url' => $notify_url,
	 'trans_time' => $_REQUEST['trans_time'],
	 'batch_no' => $_REQUEST['batch_no'],
	 'next_tag' => $_REQUEST['next_tag'],
   );
   
$url = $dsfUrl.'agentpay/batchpayquery';
echo $url,"\n";
$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id,$version);
print_r($result);
$response = json_decode($result,true);
print_r($response);
$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
echo "================返回结果=========================","\n";
echo AESDecryptResponse($encryptkey,$response['data']);
?>