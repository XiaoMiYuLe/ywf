<?php
//header("Content-Type:text/html;charset=UTF-8");
require_once '../util.php'; 
require_once '../config.php'; 

//参数数组
$paramArr = array(
     'merchant_id' => $_REQUEST['merchant_id'],
     'orig_order_no' => $_REQUEST['orig_order_no'],
	 'order_no' => $_REQUEST['order_no'],
	 'amount' => $_REQUEST['amount'],
	 'note' => $_REQUEST['note']
   );

//访问储蓄卡签约服务
$url = $apiUrl.'/fast/refund';
echo $url,"\n";

$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id);


$response = json_decode($result,true);
$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
echo $encryptkey,"\n";
echo AESDecryptResponse($encryptkey,$response['data']);
?>