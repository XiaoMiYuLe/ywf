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
 * @since      Jul 5, 2010
 * @version    SVN: $Id: Abstract.php 5517 2010-07-05 08:12:54Z woody $
 */

abstract class Zeed_OAuth_DataStore_Abstract
{
    abstract function lookupConsumer($consumerKey);
    
    abstract function lookupToken($consumer, $tokenType, $token);
    
    abstract function lookupNonce($consumer, $token, $nonce, $timestamp);
    
    /**
     * return a new token attached to this consumer
     * 
     * @param $consumer
     * @param $callback
     */
    abstract function newRequestToken($consumer, $callback = null);
    
    /**
     * return a new access token attached to this consumer
     * for the user associated with this token if the request token
     * is authorized
     * should also invalidate the request token
     * 
     * @param $token
     * @param $consumer
     * @param $verifier
     */
    abstract function newAccessToken($token, $consumer, $verifier = null);
}

// End ^ Native EOL ^ encoding
