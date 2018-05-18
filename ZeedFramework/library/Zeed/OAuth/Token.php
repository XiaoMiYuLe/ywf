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
 * @version    SVN: $Id: Token.php 5517 2010-07-05 08:12:54Z woody $
 */

class Zeed_OAuth_Token
{
    // access tokens and request tokens
    public $key;
    public $secret;
    
    /**
     * key = the token
     * secret = the token secret
     */
    function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }
    
    /**
     * generates the basic string serialization of a token that a server
     * would respond to request_token and access_token calls with
     */
    function __toString()
    {
        return "oauth_token=" . Zeed_OAuth_Util::urlencode_rfc3986($this->key) . "&oauth_token_secret=" . Zeed_OAuth_Util::urlencode_rfc3986($this->secret);
    }
}

// End ^ Native EOL ^ encoding
