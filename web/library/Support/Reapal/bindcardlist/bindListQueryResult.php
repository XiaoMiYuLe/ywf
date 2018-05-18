<?php
header("Content-Type:text/html;charset=UTF-8");
require_once '../util.php'; 
require_once '../config.php'; 

//参数数组
$paramArr = array(
     'merchant_id' => $_REQUEST['merchant_id'],
     'member_id' => $_REQUEST['member_id'],
	 'bank_card_type' => $_REQUEST['bank_card_type'],
	 'version' => '3.1.2'
);

//访问储蓄卡签约服务
$url = $apiUrl.'/fast/bindcard/list';
echo $url,"\n";

$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id);


$response = json_decode($result,true);
$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
echo $encryptkey,"\n";
echo AESDecryptResponse($encryptkey,$response['data']);
?>