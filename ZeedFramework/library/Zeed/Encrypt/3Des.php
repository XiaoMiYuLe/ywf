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
 * @since      2010-8-31
 * @version    SVN: $Id$
 */

class Zeed_Encrypt_3Des extends Zeed_Encrypt_Abstract
{
    public function __construct($data = null, $salt = null, $encrypted = false)
    {
        if (!extension_loaded('mcrypt')) {
            throw new Zeed_Exception('The mcrypt extension must be loaded for using this encrypt !');
        }

        parent::__construct($data, $salt, $encrypted);
    }

    public function encrypt()
    {
        $this->_salt = substr($this->_salt, 0, 24);

        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        $size = mcrypt_enc_get_iv_size($td);
        $this->_data = $this->_pkcs5Pad($this->_data, $size);

        $iv = mcrypt_create_iv ($size, MCRYPT_RAND);
        mcrypt_generic_init($td, $this->_salt, $iv);
        $cipher = base64_encode(mcrypt_generic($td, $this->_data));
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $cipher;
    }

    /**
     *
     * @param string $text
     * @param unknown_type $blocksize
     * @return string
     * @see http://www.rsa.com/rsalabs/node.asp?id=2127
     */
    private function _pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
}