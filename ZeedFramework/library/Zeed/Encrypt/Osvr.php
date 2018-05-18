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
 * @since      2010-8-18
 * @version    SVN: $Id$
 */

/**
 * md5(PASSWORD.SALT)
 */
class Zeed_Encrypt_Osvr extends Zeed_Encrypt_Abstract
{
    const BIT_OF_BYTE = 8;
    const PASSWD_SIZE = 16;

    const BANNED = '2';

    private static $shuffle_matrix = array(
            5,
            22,
            10,
            69,
            86,
            11,
            87,
            74,
            59,
            14,
            51,
            28,
            50,
            103,
            63,
            40,
            35,
            18,
            31,
            16,
            80,
            43,
            119,
            52,
            49,
            70,
            105,
            1,
            79,
            2,
            27,
            67,
            108,
            23,
            48,
            25,
            72,
            15,
            93,
            30,
            42,
            17,
            6,
            44,
            98,
            0,
            114,
            123,
            90,
            46,
            85,
            117,
            84,
            37,
            83,
            115,
            76,
            7,
            91,
            36,
            3,
            26,
            19,
            34,
            8,
            81,
            41,
            120,
            33,
            101,
            122,
            56,
            94,
            88,
            78,
            66,
            125,
            29,
            53,
            127,
            38,
            106,
            57,
            21,
            60,
            73,
            104,
            54,
            71,
            96,
            82,
            12,
            102,
            113,
            89,
            75,
            68,
            13,
            92,
            39,
            124,
            4,
            61,
            58,
            47,
            55,
            99,
            9,
            95,
            97,
            109,
            111,
            118,
            24,
            110,
            45,
            100,
            77,
            112,
            62,
            116,
            20,
            126,
            121,
            107,
            65,
            32,
            64);
    private static $mask = array(
            0x82,
            0x27,
            0x43,
            0x93,
            0x86,
            0x27,
            0x46,
            0x75,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0);

    public function encrypt()
    { /*{{{*/
        $in_pwd = $this->_data;
        $pwd = str_pad($in_pwd, self::PASSWD_SIZE);
        $enc_pwd = array_fill(0, self::PASSWD_SIZE, 0);
        for ($bit = 0; $bit < self::PASSWD_SIZE * self::BIT_OF_BYTE; ++ $bit) {
            $offset = self::$shuffle_matrix[$bit];
            $from_mask = 0x80 >> ($bit % self::BIT_OF_BYTE);
            $to_mask = 0x80 >> ($offset % self::BIT_OF_BYTE);
            if (ord($pwd{$bit / self::BIT_OF_BYTE}) & $from_mask) {
                $enc_pwd[$offset / self::BIT_OF_BYTE] |= $to_mask;
            }
        }
        for ($i = 0; $i < self::PASSWD_SIZE; ++ $i) {
            $enc_pwd[$i] ^= self::$mask[$i];
        }
        $tmp = '';
        foreach ($enc_pwd as $i) {
            $tmp .= sprintf("%02X", $i);
        }
        $out_pwd = '';
        for ($i = 0; $i < strlen($tmp); ++ $i) {
            $out_pwd .= chr(ord($tmp{$i}) + 1);
        }
        return base64_encode($out_pwd);
    } /*}}}*/

    public function decrypt()
    { /*{{{*/
        $in_pwd = $this->_data;
        $tmp = base64_decode($in_pwd);
        $pwd = '';
        for ($i = 0; $i < strlen($tmp); ++ $i) {
            $pwd .= chr(ord($tmp{$i}) - 1);
        }
        for ($i = 0; $i < self::PASSWD_SIZE; ++ $i) {
            $enc_pwd[$i] = hexdec(substr($pwd, $i * 2, 2));
            $enc_pwd[$i] ^= self::$mask[$i];
        }
        $pwd = array_fill(0, self::PASSWD_SIZE, 0);
        for ($bit = 0; $bit < self::PASSWD_SIZE * self::BIT_OF_BYTE; ++ $bit) {
            $offset = self::$shuffle_matrix[$bit];
            $from_mask = 0x80 >> ($offset % self::BIT_OF_BYTE);
            $to_mask = 0x80 >> ($bit % self::BIT_OF_BYTE);
            if ($enc_pwd[$offset / self::BIT_OF_BYTE] & $from_mask)
                $pwd[$bit / self::BIT_OF_BYTE] |= $to_mask;
        }
        $out_pwd = '';
        foreach ($pwd as $i) {
            $out_pwd .= chr($i);
        }
        return $out_pwd;
    } /*}}}*/
}

// End ^ Native EOL ^ encoding
