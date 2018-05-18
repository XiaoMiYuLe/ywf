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
 * @since      Jun 22, 2010
 * @version    SVN: $Id: Sha1.php 5370 2010-06-22 03:11:17Z woody $
 */

/**
 * sha1(PASSWORD.SALT)
 */
class Zeed_Encrypt_Sha1 extends Zeed_Encrypt_Abstract
{
    public function encrypt()
    {
        return sha1($this->_data.$this->_salt);
    }
}

// End ^ Native EOL ^ encoding
