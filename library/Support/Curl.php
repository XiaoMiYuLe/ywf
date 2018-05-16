<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      Jul 29, 2010
 * @version    SVN: $Id: Abstract.php 8932 2010-12-15 06:34:15Z woody $
 */

 class Support_Curl
{
    /**
     * 模拟 HTTP 协议进行提交
     *
     * @param string|array $params
     * @param string $url
     * @param string $request_method
     * @return boolean|string
     */
    public static function run($url = null, $params = null, $request_method = null)
    {
        $method = $request_method ? $request_method : 'POST';
        $userAgent = 'BLUEMOBI HTTP CLIENT';
        $ch = curl_init();
        
        $request = $params;
        
        if ($method == 'POST') {
            if (is_string($params)) {
                $request = explode('&', $params);
            }
            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        } else {
            if (is_array($params)) {
                $request = http_build_query($params, '', '&');
            }
            
            curl_setopt($ch, CURLOPT_URL, $url . "?" . $request);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $result = curl_exec($ch);
        curl_close($ch);
        
        if ($result) {
            return Support_Validate_Json::isJson($result) ? json_decode($result, true) : $result;
        }
        
        return null;
    }
}

// End ^ Native EOL ^ encoding
