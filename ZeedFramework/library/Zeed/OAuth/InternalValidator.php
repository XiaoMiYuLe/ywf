<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      Aug 10, 2010
 * @version    SVN: $Id: InternalValidator.php 5989 2010-08-11 04:56:37Z woody $
 */

/**
 * 简单验证请求是否合法, 仅限验证内部应用直接调用API
 * 
 * @author ahdong
 */
class Zeed_OAuth_InternalValidator
{
    /**
     * @var integer
     */
    public static $_timestampThreshold = 300;
    
    /**
     * 简单验证请求是否合法, 仅限内部应用直接调用API
     * 
     * @param array $consumers array('key1'=>'secret1','key2'=>'secret2')
     * @param Zeed_OAuth_Token $token
     * @param Zeed_OAuth_Request $request
     */
    public static function validate($consumers, Zeed_OAuth_Token $token = null, Zeed_OAuth_Request $request = null)
    {
        if (is_null($request)) {
            $request = Zeed_OAuth_Request::fromRequest();
        }
        $consumerKey = @$request->getParameter("oauth_consumer_key");
        if (! $consumerKey) {
            throw new Zeed_OAuth_Exception("No consumer key");
        }
        
        if (!in_array($consumerKey, array_keys($consumers))) {
            throw new Zeed_OAuth_Exception("Invalid consumer key");
        }
        
        // verify that timestamp is recentish
        $timestamp = @$request->getParameter('oauth_timestamp');
        if (! $timestamp) {
            throw new Zeed_OAuth_Exception('Missing timestamp parameter. The parameter is required');
        }
        $now = time();
        if (abs($now - $timestamp) > self::$_timestampThreshold) {
            throw new Zeed_OAuth_Exception("Expired timestamp, yours $timestamp, ours $now");
        }
        
        $consumer = new Zeed_OAuth_Consumer($consumerKey, $consumers[$consumerKey]);
        
        // verify that nonce is fresh
        $nonce = @$request->getParameter('oauth_nonce');
        if (! $nonce) {
            throw new Zeed_OAuth_Exception('Missing nonce parameter. The parameter is required');
        }
        $nonceKey = md5('zoi-'.$consumer.'-'.$token.'-'.$timestamp.'-'.$nonce);
        $cached = Zeed_Cache::instance()->load($nonceKey);
        if ($cached !== false) {
            throw new Zeed_OAuth_Exception('Replay attacks');
        } else {
            Zeed_Cache::instance()->save(1,$nonceKey,array('zoi'),self::$_timestampThreshold*2);
        }
        
        // verify signature
        $signatureMethod = self::_getSignatureMethod($request);
        $signature = $request->getParameter('oauth_signature');
        $validSig = $signatureMethod->checkSignature($request, $consumer, $token, $signature);
        if (! $validSig) {
            throw new Zeed_OAuth_Exception("Invalid signature");
        }
        
        return $request->getParameters();
    }
    
    /**
     * 获取签名方式
     * 
     * @param Zeed_OAuth_Request $request
     * @return Zeed_OAuth_Signature_Abstract
     */
    protected static function _getSignatureMethod($request)
    {
        $signatureMethod = @$request->getParameter("oauth_signature_method");
        
        if (! $signatureMethod) {
            throw new Zeed_OAuth_Exception('No signature method parameter. This parameter is required');
        }
        
        if ('HMAC-SHA1' == $signatureMethod) {
            return new Zeed_OAuth_Signature_HMACSHA1();
        } else {
            throw new Zeed_OAuth_Exception('Unsupported signature method');
        }
    }
}

// End ^ Native EOL ^ encoding
