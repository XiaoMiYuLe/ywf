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
 * @since      2010-7-22
 * @version    SVN: $Id$
 */

$urlmapping = Zeed_Config::loadGroup('urlmapping');

$config['payment_config'] = array(
    /* 接口 - 手机网站支付配置 */
    'Alipay_Mobile' => array(
            'partner' => '', //合作身份者id，以2088开头的16位纯数字
            'seller_id' => '', //收款支付宝账号
            'sign_type' => strtoupper('RSA'), //无需改动
            'cacert' => ZEED_PATH_CONF . 'certificate/Alipay/cacert.pem', //cacert.pem路径
            'private_key_path' => '', //RSA商户私钥（后缀是.pen）文件相对路径
            'notify_url' => '', //异步通知路径
            'return_url' => '', //同步通知路径
            'input_charset' => 'utf-8', //编码
			'transport' => 'http' //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
    ),
    /* 接口 - 退款有密钥支付配置 */
    'Alipay_Refund' => array(
            'partner' => '', //合作身份者id，以2088开头的16位纯数字
            'key' => '', // MD5商户私钥
            'seller_email' => '', //卖家支付宝账号
			'sign_type' => strtoupper('MD5'), //无需改动
			'cacert' => ZEED_PATH_CONF . 'certificate/Alipay/cacert.pem', //cacert.pem路径
            'notify_url' => '', //异步通知路径
            'input_charset' => 'utf-8', //编码
			'transport' => 'http' //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
    )
);

return $config;

// End ^ LF ^ UTF-8