<?php
//商户ID
//正式
$merchant_id = '100000000234069';
//测试
// $merchant_id = '100000000011015';
//商户邮箱
$seller_email = '820061154@qq.com';
// 商户私钥
//正式
$merchantPrivateKey = dirname(__FILE__).'/cert/user-rsa.pem';
//测试
// $merchantPrivateKey = dirname(__FILE__).'/cert/itrus001_pri.pem';
// $merchantPrivateKey = 'D:\\cert\\itrus001_pri.pem';
// 商户公钥
$merchantPublicKey = 'D:\\cert\\itrus001.pem';
// 融宝公钥
$reapalPublicKey = dirname(__FILE__).'/cert/itrus001.pem';
// $reapalPublicKey = 'D:\\cert\\itrus001.pem';
// APIKEy
//正式
$apiKey = 'gcd2978ef4e2gdf9g8dcd2455fg6dfcd121fee110bd3060egf98ab1e1003cc41';
//测试
// $apiKey = 'e977ade964836408243b5g2444848f7b39d09fb41c77ae2e327ffb16f905e117';
// APIUrl
//正式
$apiUrl = 'http://api.reapal.com';
//测试
// $apiUrl = 'http://testapi.reapal.com';



?>