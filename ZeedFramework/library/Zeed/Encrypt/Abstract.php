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

abstract class Zeed_Encrypt_Abstract
{
    protected $_data = null;
    protected $_salt = null;
    protected $_dataEcrypted = false;

    public function __construct($data = null, $salt = null, $encrypted = false)
    {
        if ( null !== $data )
        {
            $this->setData($data);
        }

        if ( null !== $salt )
        {
            $this->setSalt($salt);
        }

        $this->_dataEcrypted = $encrypted;
    }

    public function setData($data)
    {
        $this->_data = trim( (string) $data );
        return $this;
    }

    public function setSalt($salt)
    {
        $this->_salt = (string) $salt;
        return $this;
    }

    /**
     * 获取加密字符串
     *
     * @return string
     */
    abstract public function encrypt();

    /**
     * 获取解密字符串
     *
     * @return string
     */
    public function decrypt()
    {
        return $this->_data;
    }
}

// End ^ Native EOL ^ encoding
