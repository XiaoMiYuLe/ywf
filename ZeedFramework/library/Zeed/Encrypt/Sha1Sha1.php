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
 * @version    SVN: $Id: Sha1Sha1.php 6133 2010-08-18 00:07:41Z nroe $
 */

/**
 * sha1(sha1(PASSWORD).SALT)
 */
class Zeed_Encrypt_Sha1Sha1 extends Zeed_Encrypt_Abstract
{
    public function encrypt()
    {
        $data = $this->_dataEcrypted ? $this->_data : sha1($this->_data);
        return sha1($data.$this->_salt);
    }
}

// End ^ Native EOL ^ encoding
