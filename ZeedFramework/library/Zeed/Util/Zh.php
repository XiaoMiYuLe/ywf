<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Jul 3, 2008
 * @version    SVN: $Id: Zh.php 8996 2010-12-21 08:34:44Z xsharp $
 */

/**
 * 中文字串(GBK)转成拼音
 */
class Zeed_Util_Zh
{
    private static $_PinyinTable = null;
    
    /**
     * 汉字转成拼音
     *
     * @param string $string 汉字串
     * @param boolean $permutationAndCombination 是否进行排列组合
     * @return string|array
     */
    public static function pinyin($string, $permutationAndCombination = false)
    {
        if (is_null(self::$_PinyinTable)) {
            self::$_PinyinTable = include ZEED_PATH_3rd . '/PinyinTable.inc';
        }
        
        $flow = array();
        for($i = 0; $i < strlen($string); $i ++) {
            if (ord($string[$i]) >= 0x81 and ord($string[$i]) <= 0xfe) {
                $h = ord($string[$i]);
                if (isset($string[$i + 1])) {
                    $i ++;
                    $l = ord($string[$i]);
                    if (isset(self::$_PinyinTable[$h][$l])) {
                        array_push($flow, self::$_PinyinTable[$h][$l]);
                    } else {
                        array_push($flow, $h);
                        array_push($flow, $l);
                    }
                } else {
                    array_push($flow, ord($string[$i]));
                }
            } else {
                array_push($flow, ord($string[$i]));
            }
        }
        
        $pinyin = '';
        if (count($flow) > 0) {
            if (! $permutationAndCombination) {
                foreach ($flow as $val) {
                    if (is_array($val)) {
                        $pinyin .= ucfirst($val[0]);
                    } else {
                        $pinyin .= chr($val);
                    }
                }
            
            } else {
                foreach ($flow as $key => $val) {
                    if (! is_array($val)) {
                        $flow[$key] = array(
                                chr($val));
                    } else {
                        $flow[$key] = array_map('ucfirst', $val);
                    }
                }
                $pc = Zeed_Util_Zh::permutationAndCombination($flow);
                $pinyin = array();
                foreach ($pc as $p) {
                    $pinyin[] = implode('', $p);
                }
            }
        }
        
        return $pinyin;
    }
    
    /**
     * 将指定的字符串转为GBK
     *
     * 编码          代码页         简介
     * GB2312  CP20936  收录文字6763个(简体中文)
     * GBK     CP936    收录文字21003个(包括简体、繁体、日文、朝鲜文。兼容GB2312。)
     * GB18030 CP54936  收录文字27533个(包括简体、繁体、少数民族文字，日文、朝鲜文。兼容GBK，不兼容BIG5。)
     * BIG5    CP950    收录文字13053个(繁体中文)
     * BIG-5
     * 《Unicode、GB2312、GBK和GB18030中的汉字》: http://www.fmddlmyy.cn/text24.html
     * 
     * @param string $string
     * @return string
     */
    public static function convert2GBK($string)
    {
        if (NULL != $cs = mb_detect_encoding($string, array(
                'UTF-8',
                'GBK',
                'BIG5'))) {
            if ($cs != 'CP936' || $cs != 'CP54936') {
                $string = iconv($cs, 'GBK', $string);
            }
        }
        
        return $string;
    }
    
    /**
     * 判断中文字符集(UTF-8/GBK/BIG5)
     *
     * http://php.net/manual/en/mbstring.supported-encodings.php
     * http://www.gnu.org/software/libiconv/
     * http://www.unicode.org/
     * http://www.fmddlmyy.cn/text24.html
     * 
     * @param string $string
     * @return unknown
     */
    public static function isUGB($string)
    {
        if (function_exists('mb_detect_encoding')) {
            if (NULL != $cs = mb_detect_encoding($string, array(
                    'UTF-8',
                    'BIG5',
                    'GBK'))) {
                switch($cs) {
                    case 'CP20936' :
                        return 'GBK';
                    case 'CP936' :
                        return 'GBK';
                    case 'CP54936' :
                        return 'GBK';
                    case 'CP950' :
                        return 'BIG5';
                    case 'BIG-5' :
                        return 'BIG5';
                    case 'UTF-8' :
                        return 'UTF-8';
                    default :
                        return $cs;
                }
            }
            
            return NULL;
        }
        
        return self::_isUGB($string);
    }
    
    /**
     * 编码         第一字节                              第二字节 
     * GB2312 0xB0-0xF7(176-247)  0xA0-0xFE(160-254)
     * GBK    0x81-0xFE(129-254)  0x40-0xFE(64-254)
     * BIG5   0x81-0xFE(129-255)  0x40-0x7E(64-126),0xA1－0xFE(161-254)
     * 
     * 一般是这样辨别GBK/BIG5的
     * 1、GBK的内码的两个字节都是从A0H-FEH之间的； 
     * 2、BIG5的内码的第一个字节是80H-FFH，第二个字节是00H-FFH； 
     *
     * @param string $strtext
     * @return string 返回:UTF-8/GBK/BIG5/null
     */
    public static function _isUGB($string)
    {
        $UGB = null;
        $length = strlen($string);
        for($i = 0; $i < $length; $i ++) {
            if (($ch1 = ord($string[$i])) > 0xE0) {
                // UTF-8
                return "UTF-8";
            } elseif ($ch1 >= 0x81) {
                // 中文
                $ch2 = ord($string[$i + 1]);
                /**
                if ($ch1 >= 0xB0 && $ch1 <= 0xF7 && $ch2 >= 0xA0 && $ch2 <= 0xFE) { // GB2312
                    $GB2312found = true;
                } else {
                    $GB2312found = false;
                }
                 */
                if ($ch1 >= 0x81 && $ch1 <= 0xFE && (($ch2 >= 0x40 && $ch2 <= 0x7E) || ($ch2 >= 0xA1 && $ch2 <= 0xFE))) { //BIG5
                    $BIG5found = true;
                } else {
                    $BIG5found = false;
                }
                if ($ch1 >= 0x81 && $ch1 <= 0xFE && $ch2 >= 0x40 && $ch2 <= 0xFE) { // GBK
                    $GBKfound = true;
                } else {
                    $GBKfound = false;
                }
                
                if ($BIG5found && $GBKfound) {
                    if ($ch1 > 0xA0 && $ch1 < 0xFE && $ch2 > 0xA0 && $ch2 < 0xFE) { // GBK汉字两个字节都是从A0H-FEH之间的
                        $UGB = 'GBK';
                    }
                    
                    if ($ch2 < 0x7F) { // 看第二个字节是否小于0x7F，如果是的的话，一般是BIG5。
                        return 'BIG5';
                    }
                    // 检查下一个字
                    $i ++;
                    continue;
                } else {
                    return $BIG5found ? 'BIG5' : 'GBK';
                }
            }
        }
        
        return $UGB;
    }
    
    /**
     * 废弃
     */
    private static function _isUGB_deprecated($strtext)
    {
        $UGB = NULL;
        $length = strlen($strtext);
        for($i = 0; $i < $length; $i ++) {
            //先判断UTF-8，UTF-8的是三个字节, GBK，BIG5是两个字节,可以分离 
            if (ord(substr($strtext, $i)) > 0xE0) {
                $UGB = "UTF-8";
                break;
            } elseif (ord(substr($strtext, $i)) > 0xA1) {
                $UGB = "BIG5";
                break;
            } elseif (ord(substr($strtext, $i)) > 0x80) {
                $UGB = "GBK";
                break;
            }
        }
        
        return $UGB;
    }
    
    /**
     * From http://w3.org/International/questions/qa-forms-utf-8.html
     */
    public static function isUTF8($string)
    {
        return preg_match('%^(?:
          [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
    	)*$%xs', $string);
    }
    
    /**
     * 要解决的数学问题：算出C(a,1) * C(b, 1) * ... * C(n, 1)的组合情况，其中C(n, 1)代表从n个元素里任意取一个元素
     *
     * 要解决的实际问题样例：某年级有m个班级，每个班的人数不同，现在要从每个班里抽选一个人组成一个小组，
     * 由该小组来代表该年级参加学校的某次活动，请给出所有可能的组合
     * 
     * 需要进行排列组合的数组
     * 数组说明：该数组是一个二维数组，第一维索引代表班级编号，第二维索引代表学生编号
     * 
     * @param array $CombinList 二维数组
     * @return array
     */
    public static function permutationAndCombination($CombinList)
    {
        
        /*
        $CombinList = array(
                1 => array(
                        "Student10",
                        "Student11"),
                2 => array(
                        "Student20",
                        "Student21",
                        "Student22"),
                3 => array(
                        "Student30"),
                4 => array(
                        "Student40",
                        "Student41",
                        "Student42",
                        "Student43"));
		*/
        
        /* 计算C(a,1) * C(b, 1) * ... * C(n, 1)的值 */
        $CombineCount = 1;
        foreach ($CombinList as $Value) {
            $CombineCount *= count($Value);
        }
        
        $Result = array();
        $RepeatTime = $CombineCount;
        foreach ($CombinList as $ClassNo => $StudentList) {
            // $StudentList中的元素在拆分成组合后纵向出现的最大重复次数
            $RepeatTime = $RepeatTime / count($StudentList);
            
            $StartPosition = 1;
            
            // 开始对每个班级的学生进行循环
            foreach ($StudentList as $Student) {
                $TempStartPosition = $StartPosition;
                
                $SpaceCount = $CombineCount / count($StudentList) / $RepeatTime;
                
                for($J = 1; $J <= $SpaceCount; $J ++) {
                    for($I = 0; $I < $RepeatTime; $I ++) {
                        $Result[$TempStartPosition + $I][$ClassNo] = $Student;
                    }
                    $TempStartPosition += $RepeatTime * count($StudentList);
                }
                $StartPosition += $RepeatTime;
            }
        }
        
        return $Result;
    }
}

// End ^ LF ^ UTF-8
