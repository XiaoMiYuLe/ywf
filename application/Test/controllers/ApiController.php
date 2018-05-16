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
 * @since 2010-12-6
 * @version SVN: $Id$
 */

class ApiController extends IndexAbstract
{
    /**
     * 接口测试
     */
    public function index()
    {
        $curl = 'http://zjcf.bluemobi.cn/api';
        $method = 'POST';
        $secret = 'O]dWJ,[*g)%k"?q~g6Co!`cQvV>>Ilvw';
        
        $app = 'Order'; // 修改这里 - 模块名
        $class = '  '; // 修改这里 - 类名
        $sign = md5($app . $class . $secret);
        
        $request = array(
                'app' => $app,
                'class' => $class,
                'sign' => $sign,
                'userid' => '1',
//                 'order_number' => '2015012949515510',
//                 'payment_alias' => 'Alipay_Moblie'
                /* 修改这里 - 下方为接口所要传递的参数 */
//                 'key' => '欢唱',
//                 'city_id' => '890',
//                 'lng' => '100',
//                 'lat' => '100',
//                 'area_id' => '891',
//                 'category' => '1',
//                 'condition' => 'end_time',
        );
        
        $method = $method ? $method : 'POST';
    	$userAgent = 'TRAVELLER HTTP CLIENT ';
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

    	Zeed_Benchmark::print_r($result);
        
    }
}

// End ^ Native EOL ^ UTF-8