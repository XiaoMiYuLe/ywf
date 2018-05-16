<?php
//header("Content-Type:text/html;charset=UTF-8");
require_once '../util.php'; 
require_once '../config.php'; 

//参数数组
$paramArr = array(
        'merchant_id' => $_REQUEST['merchant_id'],   //商户在融宝的账户ID
        "member_id" => $_REQUEST['member_id'],
        "bind_id" => $_REQUEST['bind_id'],
        'order_no' => $_REQUEST['order_no'],                //商户生成的唯一订单号
        // "order_no" => $order_no,  //"rbpay_app2016022611442885503",
        "return_url" => "reapal.com",
        "notify_url" => "reapal.com",
        "terminal_type" => "mobile",
        'version' => '3.1.3'                   //版本控制默认3.0
   );
var_dump($paramArr);die;
//访问储蓄卡签约服务
$url = $apiUrl.'/fast/certificate';
// echo $url,"\n";

$result = send($paramArr, $url, $apiKey, $reapalPublicKey, $merchant_id);
// echo $result;

/*$response = json_decode($result,true);
$encryptkey = RSADecryptkey($response['encryptkey'],$merchantPrivateKey);
echo $encryptkey,"\n";
echo AESDecryptResponse($encryptkey,$response['data']);*/
?>