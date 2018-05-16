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
abstract class Api_Abstract
{

    /**
     * 是否开启模拟模式
     * true:开启；false:关闭；
     */
    public static $_sm = true;

    protected static function curl ($curl, $method = null, $request = null)
    {
        $method = $method ? $method : 'GET';
        $userAgent = 'YUMMALL HTTP CLIENT ';
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

// End ^ Native EOL ^ encoding
