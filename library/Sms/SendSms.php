<?php

class Sms_SendSms {
//测试单条下行
// testSingleMt();
//测试相同内容群发
//testMultiMt();
//测试不同内容群发
//testMultiXMt();

/**
 * 单一号码，单一内容下发,用于验证码
 *
 *  * @return String
 */
public static function testSingleMt($phone,$content) {
	
	$host="esms100.10690007.net";//接口请求地址
	
	//预定义参数，参数说明见文档
	$spid="9155";
	$spsc="00";
	$sppassword="zjgtcf9155api";
	$sa="10";
	$da=$phone;
	$dc="15";
	$sm=$content;
	//发送端口，默认80.
	$port=80;
	//拼接URI
	$request = "/sms/mt";
	$request.="?command=MT_REQUEST&spid=".$spid."&spsc=".$spsc."&sppassword=".$sppassword;
	$request.="&sa=".$sa."&da=".$da."&dc=".$dc."&sm=";
	$request.=self::encodeHexStr($dc,$sm);//下发内容转换HEX编码
	$result = self::doGetRequest($host,$port,$request);//调用发送方法发送
	return $result;
}

/**
 * 单一号码，单一内容下发，用于通知类
 *
 *  * @return String
 */
public static function testSingleMt1($phone,$content) {

    $host="esms100.10690007.net";//接口请求地址

    //预定义参数，参数说明见文档
    $spid="9155";
    $spsc="01";
    $sppassword="zjgtcf9155api";
    $sa="10";
    $da=$phone;
    $dc="15";
    $sm=$content;
    //发送端口，默认80.
    $port=80;
    //拼接URI
    $request = "/sms/mt";
    $request.="?command=MT_REQUEST&spid=".$spid."&spsc=".$spsc."&sppassword=".$sppassword;
    $request.="&sa=".$sa."&da=".$da."&dc=".$dc."&sm=";
    $request.=self::encodeHexStr($dc,$sm);//下发内容转换HEX编码
    $result = self::doGetRequest($host,$port,$request);//调用发送方法发送
    return $result;
}

public static function doGetRequest($host,$port,$request) {
	$httpGet  = "GET ". $request. " HTTP/1.1\r\n";
	$httpGet .= "Host: $host\r\n";
	$httpGet .= "Connection: Close\r\n";
	//	$httpGet .= "User-Agent: Mozilla/4.0(compatible;MSIE 7.0;Windows NT 5.1)\r\n";
	$httpGet .= "Content-type: text/plain\r\n";
	$httpGet .= "Content-length: " . strlen($request) . "\r\n";
	$httpGet .= "\r\n";
	$httpGet .= $request;
	$httpGet .= "\r\n\r\n";
	return self::httpSend($host,$port,$httpGet);
}

/**
 * 使用http协议发送消息
 *
 * @param string $host
 * @param int $port
 * @param string $request
 * @return string
 */
public static function httpSend($host,$port,$request) {
	$result = "";
	$fp = @fsockopen($host, $port,$errno,$errstr,5);
	if ( $fp ) {
		fwrite($fp, $request);
		while(! feof($fp)) {
			$result .= fread($fp, 1024);
		}
		fclose($fp);
	}else{
		return "连接短信网关超时！";//超时标志
	}
	list($header, $foo)  = explode("\r\n\r\n", $result);
	list($foo, $content) = explode($header, $result);
	$content=str_replace("\r\n","",$content);
	//返回调用结果
	return $content;
}

/**
 * encode Hex String
 *
 * @param string $dataCoding
 * @param string $binStr
 * @param string $encode
 * @return string hex string
 */
public static function encodeHexStr($dataCoding,$binStr,$encode="UTF-8"){
	//return bin2hex($binStr);
	if ($dataCoding == 15) {//GBK
		return bin2hex(mb_convert_encoding($binStr,"GBK",$encode));
	} elseif (($dataCoding & 0x0C) == 8) {//UCS-2BE
		return bin2hex(mb_convert_encoding($binStr,"UCS-2BE",$encode));
	} else {//ISO8859-1
		return bin2hex(mb_convert_encoding($binStr,"ASCII",$encode));
	}
}

}
