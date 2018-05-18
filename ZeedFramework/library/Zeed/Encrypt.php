<?php
/**
 * Playcool Project
 *
 * LICENSE
 *
 * http://www.playcool.com/license/ice
 *
 * @category   Zeed
 * @package    Zeed_Encrypt
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Jun 4, 2009
 * @version    SVN: $Id: Log.php 4637 2009-12-07 09:12:24Z xsharp $
 */

class Zeed_Encrypt
{
    /**
     * 加密字符串
     *
     * @param string $hash 加密类型，md5 对应 Zeed_Encrypt_Md5 处理
     * @param string $data 需要被加密额数据
     * @param string $key 加密密钥
     * @return string
     */
    public static function encode($hash, $data, $key = null, $encrypted = false)
    {
        $encodeClass = "Zeed_Encrypt_" . ucwords($hash);
        $encodeString = $data;

        if (class_exists($encodeClass) && is_subclass_of($encodeClass, 'Zeed_Encrypt_Abstract')) {
            $encryper = new $encodeClass($data, $key, $encrypted);
            $encodeString = $encryper->encrypt();
        }

        return $encodeString;
    }

    /**
     * 解密字符串
     *
     * @param string $hash 解密类型
     * @param string $data 需要被解密的数据
     * @param string $key 解密密钥
     * @return string
     * @see Zeed_Encrypt::encode()
     */
    public static function decode($hash, $data, $key = null)
    {
        $encodeClass = "Zeed_Encrypt_" . ucwords($hash);
        $decodeString = $data;

        if (class_exists($encodeClass) && is_subclass_of($encodeClass, 'Zeed_Encrypt_Abstract')) {

            if (method_exists($encodeClass, 'decrypt')) {
                $decrypt = new $encodeClass($data, $key);
                $decodeString = $decrypt->decrypt();
            }
        }

        return $decodeString;
    }

    /**
     * 比较参数密文与明文使用指定的加密方式后是否相等
     * 
     * @param string $encodedString4Validate
     * @param string $encodeMethod
     * @param string $data
     * @param string $key
     * @return boolean
     */
    public static function validate($encodedString4Validate, $encodeMethod, $data, $key)
    {
        $encodeClass = "Zeed_Encrypt_" . ucwords($encodeMethod);
        $encodeString = $data;

        if (class_exists($encodeClass) && is_subclass_of($encodeClass, 'Zeed_Encrypt_Abstract')) {
            $encryper = new $encodeClass($data, $key);
            $encodeString = $encryper->encrypt();
        }

        return $encodedString4Validate == $encodeString;
    }

    /**
     * 获取密码杂质，可用于密码储存算法
     *
     * @param integer $length
     * @return string
     */
    public static function generateSalt($length = 4)
    {
        $salt = '';
        for ($i = 0; $i < $length; $i ++) {
            $salt .= chr(rand(33, 126));
        }
        return $salt;
    }

    private static function strcrc32($text)
    {
        $crc = crc32($text);
        if ($crc & 0x80000000) {
            $crc ^= 0xffffffff;
            $crc += 1;
            $crc = - $crc;
        }
        return $crc;
    }
}

// End ^ LF ^ UTF-8
