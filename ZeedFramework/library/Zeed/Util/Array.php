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
 * @since      2010-9-2
 * @version    SVN: $Id: Array.php 13420 2012-10-15 07:23:32Z xsharp $
 */

class Zeed_Util_Array
{
    /**
     * 返回数据中指定键值
     * 
     * @param array $array
     * @param mixed $currentKey
     * @param integer $offset
     * @return mixed | null
     * 
     * @example
     * <code>
     *    $test_array = array(
     *        "apple" => "Red, shiny fruit",
     *        "orange" => "Orange, dull, juicy fruit",
     *        "pear" => "Usually green and odd-shaped fruit",
     *        "banana" => "Long yellow fruit that monkeys like to eat",
     *        "cantelope" => "Larger than a grapefruit",
     *        "grapefruit" => "Kind of sour"
     *    );
     *    
     *    var_dump(Zeed_Util_Array::key_relative($test_array, 'apple', 2));
     *    var_dump(Zeed_Util_Array::key_relative($test_array, 'apple', 2, 3));
     *    
     *    var_dump(Zeed_Util_Array::key_relative($test_array, "orange", -1));
     *    var_dump(Zeed_Util_Array::key_relative($test_array, "orange", -1, 3));
     * </code>
     */
    public static function key_relative($array, $currentKey, $offset = 1, $length = 1)
    {
        $keys = array_keys($array);
        $currentKeyIndex = array_search($currentKey, $keys, true);
        
        if (1 == $length) {
            if (isset($keys[$currentKeyIndex + $offset])) {
                return $keys[$currentKeyIndex + $offset];
            }
        } else {
            return array_slice($keys, $offset, $length);
        }
        
        return null;
    }
    
    /**
     * 将二维数组 key 设置为指定的值
     * 
     * @param array $array
     * @param mixed $specifiedKey
     * @return array | false
     * 
     * @example
     * <code>
     *    $test_array = array(
     *        array(
     *            "apple" => "Red, shiny fruit",
     *            "orange" => "Orange, dull, juicy fruit",
     *            "pear" => "Usually green and odd-shaped fruit",
     *            "banana" => "Long yellow fruit that monkeys like to eat",
     *            "cantelope" => "Larger than a grapefruit",
     *            "grapefruit" => "Kind of sour"),
     *        array(
     *            "orange" => "Orange, dull, juicy fruit",
     *            "pear" => "Usually green and odd-shaped fruit",
     *            "banana" => "Long yellow fruit that monkeys like to eat",
     *            "cantelope" => "Larger than a grapefruit",
     *            "grapefruit" => "Kind of sour"),
     *    
     *    
     *    var_dump(Zeed_Util_Array::set_key($test_array, 'apple'));
     *    var_dump(Zeed_Util_Array::set_key($test_array, 'orange'));
     * </code>
     */
    public static function set_key($array, $specifiedKey)
    {
        if (!is_array(current($array))) {
            return false;
        }
        
        $newArray = array();
        
        foreach ($array as $subKey => $subArray) {
            $keys = array_keys($subArray);
            $specifiedKeyIndex = array_search($specifiedKey, $keys, true);
            
            /**
             * 当指定 $specifiedKey 未找到时，不进行处理第一层的 KEY
             */
            if (false !== $specifiedKeyIndex && isset($keys[$specifiedKeyIndex])) {
                $subKey = $subArray["{$keys[$specifiedKeyIndex]}"];
            }
            
            $newArray[$subKey] = $subArray;
        }
        
        return $newArray;
    }
    
    /**
     * 递归地比较两个数组.
     * 返回一个数组，该数组包括了所有在 $aArray1 中但是不在任何其它参数数组中的值。注意键名保留不变
     * 
     * @author firegun@terra.com.br
     * @param array $aArray1
     * @param array $aArray2
     * @return array
     */
    public static function arrayRecursiveDiff($aArray1, $aArray2) {
        $aReturn = array();
         
        foreach ($aArray1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $aArray2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = Zeed_Util_Array::arrayRecursiveDiff($mValue, $aArray2[$mKey]);
                    if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                } else {
                    if ($mValue != $aArray2[$mKey]) {
                        $aReturn[$mKey] = $mValue;
                    }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
         
        return $aReturn;
    }
}

// End ^ LF ^ UTF-8
