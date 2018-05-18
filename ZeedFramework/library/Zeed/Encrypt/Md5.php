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
 * @since      2010-6-18
 * @version    SVN: $Id$
 */

/**
 * md5(PASSWORD.SALT)
 */
class Zeed_Encrypt_Md5 extends Zeed_Encrypt_Abstract
{
    public function encrypt()
    {
        return md5($this->_data.$this->_salt);
    }
}

// End ^ Native EOL ^ encoding
