<?php
header("Content-Type:text/html;charset=utf-8");
require_once 'util.php'; 
require_once 'config.php'; 

//参数数组
$time2 = date("y-m-d h:i:s",time());
$paramArr = array(
      'charset' => $charset,
	  'notify_url' => $notify_url,
	  'trans_time' => $time2,
	  'batch_no' => $_REQUEST['batch_no'],
	  'batch_count' => $_REQUEST['batch_count'],
	  'batch_amount' => $_REQUEST['batch_amount'],
	  'pay_type' => $_REQUEST['pay_type'],
	  'content' => $_REQUEST['content'],
   );
   
$url = $dsfUrl.'agentpay/pay';
//echo $url,"\n";
$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id,$version);
//print_r($result);
$response = json_decode($result,true);
//print_r($response);
$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
$data = AESDecryptResponse($encryptkey,$response['data']);

$data = json_decode($data);
var_dump($data);

?>
