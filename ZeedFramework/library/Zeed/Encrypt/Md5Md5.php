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
 * @version    SVN: $Id: Md5Md5.php 5814 2010-08-03 03:14:05Z woody $
 */

/**
 * md5(md5(PASSWORD).SALT)
 */
class Zeed_Encrypt_Md5Md5 extends Zeed_Encrypt_Abstract
{
    public function encrypt()
    {
        $data = $this->_dataEcrypted ? $this->_data : md5($this->_data);
        return md5($data.$this->_salt);
    }
}

// End ^ Native EOL ^ encoding
