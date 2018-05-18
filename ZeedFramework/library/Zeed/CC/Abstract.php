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
 * @since      2010-7-9
 * @version    SVN: $Id$
 */

class Zeed_CC_Abstract
{
    /**
     * 信用卡序列号名称
     *
     * @var string
     */
    protected $_name = 'default';

    /**
     * 信用卡序列号前缀，可指定不同前缀
     *
     * @var array
     */
    protected $_allowPrefix = array(
            '00');

    /**
     * 信用卡序列号长度，可以指定不同的长度
     *
     * array
     */
    protected $_allowLength = array();

    /**
     * 信用卡序号
     *
     * @var unknown_type
     */
    public $ccnumber = null;

    /**
     * 信用卡设置长度
     *
     * @var integer
     */
    public $length = null;

    /**
     * 信用卡设置前缀
     *
     * @var numeric
     */
    public $prefix = '';


    /**
     *
     * @param integer $length
     * @param numeric $prefix
     */
    public function __construct($length = null, $prefix = null)
    {
        if ( null !== $length) {
            $this->length = $length;
        }

        if ( null !== $prefix) {
            $this->prefix = $prefix;
        }
    }

    public static function sprintf($ccnumber, $splitLen = 4, $glue = ' ', $mark = null)
    {
        $string = '';

        if ( strlen($ccnumber) <= $splitLen ){
            return $ccnumber;
        }

        /**
         * 将后几位替换成指定字符，
         */
        if ( is_string($mark))
        {
            $markLen = strlen($mark);
            $ccnumber = substr($ccnumber, 0, strlen($ccnumber) - $markLen) . $mark;
        }

        $ccnumber = str_split($ccnumber, $splitLen);
        $string = implode($glue , $ccnumber);


        return $string;
    }

    /**
     * 生成信用卡序列号
     *
     * @param numeric $prefix 序列号前缀
     * @param integer $length 序列号长度
     * @return string|null 当指定长度以及前缀不符合要求时，生成失败返回 null
     */
    public function generate($length = null, $prefix = null)
    {
        if (null !== $length) {
            $this->length = $length;
        }

        if (null !== $prefix) {
            $this->prefix = $prefix;
        }

        $sPrefixIndex = array_rand($this->_allowPrefix, 1);
        $ccnumber = $this->_allowPrefix[$sPrefixIndex] . $this->prefix;

        if (! in_array($this->length, $this->_allowLength)) {
            return null;
        }

        # generate digits
        while (strlen($ccnumber) < ($this->length - 1)) {
            $ccnumber .= rand(0, 9);
        }

        # Calculate sum
        $sum = 0;
        $pos = 0;

        $reversedCCnumber = strrev($ccnumber);

        while ($pos < $this->length - 1) {

            $odd = $reversedCCnumber[$pos] * 2;
            if ($odd > 9) {
                $odd -= 9;
            }

            $sum += $odd;

            if ($pos != ($this->length - 2)) {

                $sum += $reversedCCnumber[$pos + 1];
            }
            $pos += 2;
        }

        # Calculate check digit
        $checkdigit = ((floor($sum / 10) + 1) * 10 - $sum) % 10;
        $ccnumber .= $checkdigit;

        $this->ccnumber = $ccnumber;

        if (!$this->isVaild($this->ccnumber)) {
            $this->ccnumber = null;
        }

        return $this->ccnumber;
    }

    public function isVaild($ccnumber = null)
    {
        if (null === $ccnumber) {
            $ccnumber = $this->ccnumber;
        }

        if (empty($ccnumber)) {
            return false;
        }

        //clean up data
        $ccnumber = preg_replace('#[^0-9]+#', '', $ccnumber);

        $map = array_merge(range(0, 9), range(0, 8, 2), range(1, 9, 2));
        $sum = 0;
        $last = strlen($ccnumber) - 1;
        for ($i = 0; $i <= $last; $i ++) {
            $sum += $map[$ccnumber[$last - $i] + ($i & 1) * 10];
        }

        # simple vaild
        if ($sum % 10 == 0) {
            $ccLength = $this->_allowLength;
            $ccPrefix = $this->_allowPrefix;

            //Test card length.
            $lengthValid = false;
            foreach ($ccLength as $x) {
                if (strlen($ccnumber) == $x)
                    $lengthValid = true;
            }

            /**
             * 序列号长度错误
             */
            if (! $lengthValid) {
                return false;
            }

            //
            $prefixValid = false;
            foreach ($ccPrefix as $x) {
                if (strpos($ccnumber, $x) === 0)
                    $prefixValid = true;
            }

            /**
             * 序列号前缀不符合
             */
            if (! $prefixValid) {
                return false;
            }

            //mod 10 test again
            $digitArray = array();
            $cnt = 0;
            $cardTemp = strrev($ccnumber);
            for ($i = 1; $i <= strlen($cardTemp) - 1; $i = $i + 2) {
                $t = substr($cardTemp, $i, 1);
                $t = $t * 2;
                if (strlen($t) > 1) {
                    $tmp = 0;
                    for ($s = 0; $s < strlen($t); $s ++) {
                        $tmp = substr($t, $s, 1) + $tmp;
                    }
                } else {
                    $tmp = $t;
                }
                $digitArray[$cnt ++] = $tmp;
            }
            $tmp = 0;
            for ($i = 0; $i <= strlen($cardTemp); $i = $i + 2) {
                $tmp = substr($cardTemp, $i, 1) + $tmp;
            }
            $result = $tmp + array_sum($digitArray);
            if ($result % 10 == 0) {
                return true;
            } else {
                return false;
            }
        }

        return false;
    }
}

// End ^ Native EOL ^ encoding
