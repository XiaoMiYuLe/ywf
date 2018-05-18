<?php
/**
 * Baidu API
 */

class Widget_Baidu_Abstract
{
    /**
     * CURL 公共方法
     */
    protected static function curl($curl, $method = null, $request = null)
    {
        $method = $method ? $method : 'GET';
        $userAgent = 'BAIDU HTTP CLIENT ';
        $ch = curl_init();
        
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_URL, $curl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        } else {
            $curl .= '?' . $request;
            curl_setopt($ch, CURLOPT_URL, $curl);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }
}
