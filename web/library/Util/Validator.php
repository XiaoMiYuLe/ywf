<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-26
 * @version SVN: $Id$
 */

/**
 * 接口验证参数, 验证器, 统一封装
 * @author asc
 *
 */
class Util_Validator
{
    /**
     * 验证手机号码
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 验证正则 有默认值
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_mobile($obj, $msg='', $reg='') {
        $code = true;
        // 判断语句
        $reg || $reg = "/^1[34578][0-9]{1}[0-9]{8}$/";
        if ($code && !preg_match($reg, $obj)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证姓名
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 验证正则 有默认值
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_name($obj, $msg='', $reg='') {
        $code = true;
        // 判断语句
        $reg || $reg = '/^[\S]{1,20}$/';
        if ($code && !preg_match($reg, $obj)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证邮箱
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 验证正则 有默认值
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_email($obj, $msg='', $reg='') {
        $code = true;
        // 判断语句
        $reg || $reg = '/^[A-Za-z0-9]([A-Za-z0-9]*[-_]?[A-Za-z0-9]+)*@([A-Za-z0-9]*[-_]?[A-Za-z0-9]+)+[\.][A-Za-z]{2,3}([\.][A-Za-z]{2})?$/';
        if ($code && !preg_match($reg, $obj)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证密码
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 验证正则 有默认值
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_pwd($obj, $msg='', $reg='') {
        $code = true;
        // 判断语句
        $reg || $reg = '/(?!^[0-9]+$)(?!^[A-z]+$)(?!^[^A-z0-9]+$)^.{6,16}$/';
        if ($code && !preg_match($reg, $obj)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证确认密码
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 原密码 必填
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_pwd2($obj, $msg='', $reg) {
        $code = true;
        // 判断语句
        if ($code && $obj!=$reg) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证支付密码
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 验证正则 有默认值
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_paypwd($obj, $msg='', $reg='') {
        $code = true;
        // 判断语句
        $reg || $reg = '/^[0-9]{6}$/';
        if ($code && !preg_match($reg, $obj)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证验证码
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 验证正则 有默认值
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_vcode($obj, $msg='', $reg='') {
        $code = true;
        // 判断语句
        $reg || $reg = '/^[0-9]{6}$/';
        if ($code && !preg_match($reg, $obj)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证银行卡卡号
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 验证正则 有默认值
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_bankcard($obj, $msg='', $reg='') {
        $code = true;
        // 判断语句
        $reg || $reg = '/^\d{16,19}$/';
        if ($code && !preg_match($reg, $obj)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证身份证号码
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param varchar $reg 验证正则 有默认值
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_idcard($obj, $msg='', $reg='') {
        $code = true;
        // 判断语句
        $reg || $reg = '/(^\d{15}$)|(^\d{17}(\d|X|x)$)/';
        if ($code && !preg_match($reg, $obj)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证金额
     * @param unknown $obj 需要判断的对象
     * @param varchar $msg 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false

     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function test_money($obj, $msg='', $min = 0) {
        $code = true;
        // 判断语句
        if ($code && (!is_numeric($obj) || $obj<=0 || $obj<$min)) {
        	$code = false;
        }
        
        // 判断语句, 判断变量
        if (!$code && $msg) {
            throw new Zeed_Exception($msg);
        } else {
            return $code;
        }
    }
    /**
     * 验证数字
     * @param unknown $number 需要判断的数字
     * @param varchar $message 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param integer $lt 数字范围内, 最小的数字, <= , 如果不填, 默认为0 注意, 在php中 0=null,
     * @param integer $gt 数字范围内, 最大的数字, <  默认为'' 在php中 0=null,
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function numberValidator($number, $message = null, $lt = 0, $gt = '') {
        $boolean = false;
        // 判断语句, 判断变量
        if ( is_numeric($number)) {
            // 判断语句, 判断变量
            if (is_numeric($lt) && is_numeric($gt) && $lt <= $number && $number < $gt) {
                $boolean = true;
            } else if (is_numeric($lt) && !is_numeric($gt) && $lt <= $number) {
                $boolean = true;
            } else if(!is_numeric($lt) && is_numeric($gt) && $number < $gt){
                $boolean = true;
            }
            
        }
        
        // 判断语句, 判断变量
        if (!$boolean && $message!=null) {
            throw new Zeed_Exception($message);
        } else {
            return $boolean;
        }
    }

    /**
     * 验证字符串
     * @param unknown $varchar 需要验证的字符串
     * @param varchar $message 需要throw 的信息, 如果存在, 则抛出错误, 如果不存在, 则 返回 true或false
     * @param integer $lt 数字范围内, 最小的数字, <= , 如果不填, 默认为0
     * @param integer $gt 数字范围内, 最大的数字, < 
     * @throws Zeed_Exception 如果$message 不为 null 则判断错误后, throw $message
     * @return boolean 如果$message 为 null, 则 验证正确返回true, 验证错误返回false
     */
    public static function varcharValidator($varchar, $message = null, $lt = 0, $gt = null ) {
        $boolean = false;
        // 判断语句, 判断变量
        if (isset($varchar)) {
            // 判断语句, 判断变量
            if (is_numeric($lt) && is_numeric($gt) && $lt <= strlen($varchar) && strlen($varchar) < $gt) {
                $boolean = true;
            } else if (is_numeric($lt) && ! is_numeric($gt) && $lt <= strlen($varchar)) {
                $boolean = true;
            } else if(! is_numeric($lt) && is_numeric($gt) && strlen($varchar) < $gt){
                $boolean = true;
            }
        }
    
        // 判断语句, 判断变量
        if (!$boolean && $message!=null) {
            throw new Zeed_Exception($message);
        } else {
            return $boolean;
        }
    }
    
    /**
     * 判断字符串是否在array里面
     * @param unknown $varchar 需要 验证的字符, 建议首先进行strtolower 或者strtoupper
     * @param array $array 验证需要的数组
     * @param string $message 如果$message存在, 不为null 则, 验证错误后throw $message
     * @param string $replace 如果$replace存在,不为null, 则, 验证错误后, 返回需要替换的$replace.
     * @throws Zeed_Exception 
     * @return string|boolean 如果 $message, $replace都不存在未null, 则 验证正确返回true, 验证错误返回false
     */
    public static function varcharInArrayValidator($varchar, $array, $message =null, $replace = null) {
        $boolean = false;
        // 判断语句, 判断变量
        if (in_array($varchar, $array)) {
            $boolean = true;
        }
        
        // 判断语句, 判断变量
        if (!$boolean && $replace !=null) {
            return $replace;
        } else if (!$boolean && $message!=null) {
            throw new Zeed_Exception($message);
        } else {
            return $boolean;
        }
    }
}
?>