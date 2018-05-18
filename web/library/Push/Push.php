<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-26
 * @version SVN: $Id$
 */
// 极光推送
class Trend_Helper_Push
{
//     private  $_appkeys = '2ebe6e27a693838ead71c458';
//     private  $_masterSecret = '784262e048d0358a322d66b9';

    
    /*  private  $_appkeys = 'dc73f386e9e40dcb659506ea';
     private  $_masterSecret = '9c5ed16c02f1cea635564566';
      */
    
     private  $_appkeys = '74e7fde8e3f4e2753e69990d';
     private  $_masterSecret = '6abf34b43a4ecab000ff77c2';
     
    
	private $tags = '';
	private $message = '';
	private $alias = '';
	private $title = '';
	private $registrationid = '';
	private $options = '';
	private $type = '';

	public function getTags()
	{
		return $this->tags;
	}

	public function setRegistrationid($registrationid)
	{
		$setregistrationid = "";
		if ((is_array($registrationid) && count($registrationid) >= 0)) {
			$setregistrationid = '"registration_id" : [ "'.implode('","', $registrationid).'" ]';
		} elseif (is_string($registrationid) && strlen($registrationid) > 0) {
			$setregistrationid = '"registration_id" : [ "'.$registrationid.'" ]';
		}
		$this->registrationid = $setregistrationid;
	}
	
	public function setOptions($options)
	{
		$options_str = "";
		if (is_string($options) && strlen($options) > 0) {
			$options_str = $options;
		}
		$this->options = $options_str;
	}

	public function getRegistrationid()
	{
		return $this->registrationid;
	}

	public function setTags($tags)
	{
		$settags = "";
		if ((is_array($tags) && count($tags) >= 0)) {
			$settags = '"tag" : [ "'.implode('","', $tags).'" ]';
		} elseif (is_string($tags) && strlen($tags) > 0) {
			$settags = '"tag" : [ "'.$tags.'" ]';
		}

		$this->tags = $settags;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage($message)
	{
		$this->message = $message;
		return $this;
	}

	public function getAlias()
	{
		return $this->alias;
	}

	public function setAlias($alias)
	{
		$setalias = '';
		if ((is_array($alias) && count($alias) >= 0)) {
			$setalias = '"alias" : [ "'.implode('","', $alias).'" ]';
		} elseif (is_string($alias) && strlen($alias) > 0) {
			$setalias = '"alias" : [ "'.$alias.'" ]';
		}
		$this->alias = $setalias;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}
    
	public function getType()
	{
	    return $this->type;
	}

	public function setType($type)
	{
	    $this->type = $type;
	    return $this;
	}
	/**
	 * 发送 极光推送 请求
	 * @param unknown_type $url
	 * @param unknown_type $param
	 * @param unknown_type $header
	 * @return boolean|mixed
	 */
	private function request_post($url="",$param="",$header="") {
		if (empty($url) || empty($param)) {
			return false;
		}
		$postUrl = $url;
		$curlPost = $param;
		$ch = curl_init();//初始化curl
		curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
		curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
		// 增加 HTTP Header（头）里的字段
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		// 终止从服务端进行验证
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$data = curl_exec($ch);//运行curl
		curl_close($ch);
		return $data;
	}

	public function run()
	{
		$url = 'https://api.jpush.cn/v3/push';
		$base64=base64_encode("$this->_appkeys:$this->_masterSecret");
		$header=array("Authorization: Basic {$base64}","Content-Type:application/json");

		/**
		 * 设置 audience 别名设置，如果 alias为空，则推送给所有人
		*/
		if ($this->alias != "") {
			$audience[] = $this->alias;
		}

		if ($this->tags != "") {
			$audience[] = $this->tags;
		}

		if ($this->registrationid != "") {
			$audience[] = $this->registrationid;
		}
		
		if ($this->options != "") {
			$options = $this->options;
		}

		if (count($audience) > 0) {
			$audience = '{ ' . implode(",", $audience) . ' }';
		} else {
			$audience = '"all"';
		}

		$type = $this->type;
		if($type == 1){
// 		    IOS
		    $param='{"platform":"ios","audience":'.$audience.',"notification": {"ios": {"alert": "'.$this->message.'","sound": "default","badge": "+1","extras": {"newsid": 321,"my_key" : "a value"}}}}';
//             $param = '{"platform": "all","audience":'.$audience.',"notification": {"ios": {"alert": "Hi, JPush!","sound": "default","badge": "+1","extras": {"newsid": 321}}},"message": {"msg_content": "'.$this->message.'","content_type": "text","title": "'.$this->title.'","extras": {"key": "value"}},}';         
		}else if($type == 2){
		    // 安卓
		    $param='{"platform":"android","audience":'.$audience.',"notification": {"android": {"alert": "'.$this->message.'","title" : "'.$this->title.'","builder_id" : 3,"extras": {"newsid": 321,"my_key" : "a value"}}}}';
		}else{
    		// 组合需要发送的json字符串
    		$param='{"platform":"all","audience":'.$audience.',"notification" : {"alert" : "'.$this->message.'"},"message":{"msg_content":"'.$this->message.'","title":"'.$this->title.'"}}';
		}
// 		$param='{"platform":"all","audience":'.$audience.',"notification" : {"alert" : "'.$this->message.'"},"sound": "default","badge": "+1","message":{"msg_content":"'.$this->message.'","title":"'.$this->title.'"}}';
// 		print_r($param);die;
		$res = $this->request_post($url,$param,$header);
		$res_arr = json_decode($res, true);
		return $res_arr;
	}
	
	public function report($params)
	{
	    // 极光推送received的url
	    $url = 'https://report.jpush.cn/v3/received';
	
	    $method = 'GET';
	    $userAgent = 'Customer HTTP CLIENT';
	    $ch = curl_init();
	
	    if ($method == 'POST') {
	        curl_setopt($ch, CURLOPT_URL, $url);
	        curl_setopt($ch, CURLOPT_POST, true);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
	    } else {
	        curl_setopt($ch, CURLOPT_URL, $url . "?msg_ids=" . $request);
	    }
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	    $data = curl_exec($ch);//运行curl
	     
	    curl_close($ch);
	    return $data;
	}
	
	/**
	 * 错误代码解析
	 * @param string $params
	 */
	public static function mapErrorCode($errorCode)
	{
	    $errorCode = (string)$errorCode;
	
	    $errorSubmitList = array(
	    '1000'  => '系统内部错误',
	    '1001'  => '只支持 HTTP Post 方法',
	    '1002'  => '缺少了必须的参数',
	    '1003'  => '参数值不合法',
	    '1004'  => '验证失败',
	    '1005'  => '消息体太大',
	    '1008'  => 'app_key参数非法',
	    '1011'  => '没有满足条件的推送目标',
	    '1020'  => '只支持 HTTPS 请求',
	    '1030'  => '内部服务超时',
	    
	    		);
	
	    return array_key_exists($errorCode, $errorSubmitList) ? $errorSubmitList[$errorCode] : '未知错误';
	}
}
// End ^ Native EOL ^ UTF-8
