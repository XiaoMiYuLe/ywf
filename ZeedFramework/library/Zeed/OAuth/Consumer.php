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
 * @version    SVN: $Id: Consumer.php 5517 2010-07-05 08:12:54Z woody $
 */

class Zeed_OAuth_Consumer
{
    public $key;
    public $secret;
    public $callbackUrl;
    
    public function __construct($key, $secret, $callbackUrl = NULL)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->callbackUrl = $callbackUrl;
    }
    
    public function __toString()
    {
        return "Zeed_OAuth_Consumer[key=$this->key,secret=$this->secret]";
    }
}

// End ^ Native EOL ^ encoding
