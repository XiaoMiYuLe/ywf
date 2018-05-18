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
 * @package    Zeed_Util
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Util.php 14014 2013-02-06 06:19:44Z xsharp $
 */

class Zeed_Util
{
    
    /**
     * 创建多级目录,如果某级目录不存在则依次创建
     *
     * @author xsharp@gmail.com
     * @param String $path
     * @param Integer $mode
     */
    public static function mkpath($path, $mode = 0777)
    {
        $sysmask = umask(0);
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('/(?:\/(?!\w))/', '$1', $path);
        $dirs = explode('/', $path);
        $path = $dirs[0];
        $flag = true;
        for ($i = 1; $i < count($dirs); $i ++) {
            $path .= '/' . $dirs[$i];
            if (@! is_dir($path)) {
                if (@! mkdir($path, $mode)) {
                    $flag = false;
                }
            }
        }
        umask($sysmask);
        
        return $flag;
    }
    
    /**
     * Redirector (refresh,header,post,javascript)
     *
     * @param String $factory Type:Refresh,Header,Post,Javascript(Js)
     * @param String $goUrl
     * @param Integer $delayTime
     * @param String $note Extra info
     * @return Redirector
     */
    public static function redirector($factory = 'refresh', $goUrl = null, $delayTime = 0, $note = null)
    {
        return new Zeed_Util_Redirector($factory, $goUrl, $delayTime, $note);
    }
    
    /**
     * Get client ip
     *
     * @return String (IP)
     */
    public static function clientIP()
    {
        // -----------------------------------------
        // Sort out the accessing IP
        // (Thanks to Cosmos and schickb)
        // -----------------------------------------
        $addrs = array();
        
        if (! empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            foreach (array_reverse(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])) as $x_f) {
                $x_f = trim($x_f);
                
                if (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $x_f)) {
                    $addrs[] = $x_f;
                }
            }
            
            if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $addrs[] = $_SERVER['HTTP_CLIENT_IP'];
            }
            
            if (isset($_SERVER['HTTP_PROXY_USER'])) {
                $addrs[] = $_SERVER['HTTP_PROXY_USER'];
            }
        }
        
        $addrs[] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        // -----------------------------------------
        // Do we have one yet?
        // -----------------------------------------
        foreach ($addrs as $ip) {
            if ($ip) {
                $match = array();
                preg_match('/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/', $ip, $match);
                
                $ip_address = $match[1] . '.' . $match[2] . '.' . $match[3] . '.' . $match[4];
                
                if ($ip_address and $ip_address != '...') {
                    return $ip_address;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Setcookie 加强版
     *
     * @param String $name
     * @param String $ |Integer $value
     * @param Intefer $timeout 小甜饼超时时间,单位为秒
     */
    public static function setcookie($name, $value = "", $timeout = 0)
    {
        $expire = time() + $timeout;
        
        if ($_SERVER['SERVER_PORT'] == "443") { // Using SSL
            $secure = 1;
        } else {
            $secure = 0;
        }
        setcookie($name, $value, $expire, '/', '', $secure);
    }
    
    /**
     * 生成随机不重复字串
     * m打头为同m+MD5的字串，长度33,s打头为s+SHA1的字串，长度为41
     *
     * @return string
     */
    public static function getRandomString()
    {
        $computer = @$_SERVER["SERVER_NAME"] . '/' . @$_SERVER["SERVER_ADDR"];
        $long = (rand(0, 1) ? '-' : '') . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(100, 999) . rand(100, 999);
        $microtime = microtime(true);
        return rand(0, 1) ? 's' . sha1($computer . $long . $microtime) : 'm' . md5($computer . $long . $microtime);
    }
    
    /**
     * Generate a random string for image verification
     *
     * @param integer Length of result
     * @param boolean 是否打开计数器模式，如果设置为 true，那么在一次执行程序中使用计数器生成随机数
     * @param boolean 如果设置为 true，那么在非计数器模式下将返回数字大小写组成的字母
     *
     * @return string
     */
    public static function getRandomChar($length = 6, $counter = false, $morechar = true)
    {
        static $_somechars = '234689ABCEFGHJMNPQRSTWY', $_morechars = '234689ABCEFGHJKMNPQRSTWXYZabcdefghjkmnpstwxyz', $counterNumber = array();
        $char = '';
        
        if ($counter) {
            if (! isset($counterNumber[$length])) {
                $counterNumber[$length] = 1;
            }
            
            $char = dechex($counterNumber[$length]);
            $char = str_pad($char, $length, '0', STR_PAD_LEFT);
            $counterNumber[$length] ++;
        } else {
            for ($x = 1; $x <= $length; $x ++) {
                $chars = ($morechar || $x <= 3 || $x == $length) ? $_morechars : $_somechars;
                $number = mt_rand(1, strlen($chars));
                $char .= substr($chars, $number - 1, 1);
            }
        }
        
        return $char;
    }
    
    /**
     * 创建随机字串
     *
     * @param integer $len 字串长度
     * @return string
     */
    public static function genRandomString($len = 16)
    {
        $chars = array(
                "a", 
                "b", 
                "c", 
                "d", 
                "e", 
                "f", 
                "g", 
                "h", 
                "i", 
                "j", 
                "k", 
                "l", 
                "m", 
                "n", 
                "o", 
                "p", 
                "q", 
                "r", 
                "s", 
                "t", 
                "u", 
                "v", 
                "w", 
                "x", 
                "y", 
                "z", 
                "A", 
                "B", 
                "C", 
                "D", 
                "E", 
                "F", 
                "G", 
                "H", 
                "I", 
                "J", 
                "K", 
                "L", 
                "M", 
                "N", 
                "O", 
                "P", 
                "Q", 
                "R", 
                "S", 
                "T", 
                "U", 
                "V", 
                "W", 
                "X", 
                "Y", 
                "Z", 
                "0", 
                "1", 
                "2", 
                "3", 
                "4", 
                "5", 
                "6", 
                "7", 
                "8", 
                "9");
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = '';
        for ($i = 0; $i < $len; $i ++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        
        return $output;
    }
    
    /**
     * 创建随机"可读"单词
     *
     * @param integer $wordLen 长度
     * @param boolean $strUseNumbers 是否使用数字
     * @return string
     */
    public static function genRandomWord($wordLen, $strUseNumbers = true)
    {
        static $V = null;
        static $VN = null;
        static $C = null;
        static $CN = null;
        
        if (! is_array($V)) {
            $V = str_split("aeiouy");
            $VN = str_split("aeiouy23456789");
            $C = str_split("bcdfghjkmnpqrstuvwxz");
            $CN = str_split("bcdfghjkmnpqrstuvwxz23456789");
        }
        
        if ($strUseNumbers) {
            $vowels = $VN;
            $consonants = $CN;
        } else {
            $vowels = $V;
            $consonants = $C;
        }
        
        $vLen = count($vowels) - 1;
        $cLen = count($consonants) - 1;
        shuffle($vowels);
        shuffle($consonants);
        
        $word = '';
        for ($i = 0; $i < $wordLen; $i = $i + 2) {
            $consonant = $consonants[mt_rand(0, $cLen)];
            $vowel = $vowels[mt_rand(0, $vLen)];
            $word .= $consonant . $vowel;
        }
        if (strlen($word) > $wordLen) {
            $word = substr($word, 0, $wordLen);
        }
        
        return $word;
    }
    
    /**
     * 获取邮箱登录地址
     * 如 abc@163.com 将返回 URL http://mail.163.com/
     *
     * @param string $mailAddress
     */
    public static function getMailLoginUrl($mailAddress)
    {
        $mailLoginUrl = '';
        
        $list = Zeed_Config::loadGroup('mail.login_url_list');
        if ($list && ($pos = strpos($mailAddress, '@')) && $pos > 0) {
            $id = substr($mailAddress, $pos + 1);
            
            if (isset($list[$id])) {
                $mailLoginUrl = trim($list[$id]);
            }
        }
        
        return $mailLoginUrl;
    }
    
    public static function sleep($sec, $echoStr = '.')
    {
        $sec = (int) $sec;
        
        for ($i = 0; $i <= $sec; $i ++) {
            if ($echoStr) {
                echo $echoStr;
                self::flush();
            }
            sleep(1);
        }
    }
    
    public static function println($str, $flush = true, $newline = true, $color = true)
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            if ($newline) {
                $str .= '<br />';
            }
        } else {
            if ($newline) {
                $str .= "\n";
            }
            
            if ($color) {
                $str = Zeed_Console_Color::convert($str);
            }
        }
        
        echo $str;
        
        if ($flush) {
            self::flush();
        }
    }
    
    /**
     * 刷新PHP程序的缓冲，而不论PHP执行在何种情况下（CGI ，web服务器等等）。
     */
    public static function flush()
    {
        if (! defined('ZEED_IN_CONSOLE') && ! headers_sent()) {
            echo (str_repeat(' ', 256) . "\n");
        }
        
        // check that buffer is actually set before flushing
        if (ob_get_length()) {
            @ob_flush();
            @flush();
            @ob_end_flush();
        }
        @ob_start();
    }
    
    /**
     * 分割字符串
     *
     * @param string $delimiter 分隔符，不对转义的分隔符进行分割如 \|
     * @param string $string 需要被分割的字符串
     * @return array|false 如果定界符 $delimiter 为空字符号返回 false
     * @see explode()
     */
    public static function explode($delimiter, $string)
    {
        $exploded = explode($delimiter, $string);
        if (is_array($exploded)) {
            $joinString = '';
            foreach ($exploded as $id => $partString) {
                if (strcmp(substr($partString, - 1, 1), "\\") === 0) {
                    $joinString .= $partString;
                    unset($exploded[$id]);
                } else {
                    if (strlen($joinString) > 0) {
                        $exploded[$id] = $joinString . $partString;
                        $joinString = '';
                    }
                }
            }
        }
        
        return $exploded;
    }
    
    /**
     * 获取文件后缀
     * 
     * @param string $filename
     * @return string 小写的文件后缀名
     */
    public static function fileExtension($filename)
    {
        return strtolower(substr(strrchr($filename, '.'), 1));
    }
}

// End ^ LF ^ UTF-8
