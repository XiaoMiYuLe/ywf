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
		 'detail_no' => $_REQUEST['detail_no'],
	   );
	   
	$url = $dsfUrl.'agentpay/singlepayquery';
	$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id,$version);
	$response = json_decode($result,true);
	$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
	echo "================返回结果=========================","\n";
	echo AESDecryptResponse($encryptkey,$response['data']);
?>