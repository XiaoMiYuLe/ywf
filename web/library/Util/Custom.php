<?php
/**
 * 自定义快捷帮助方法
 * @author Administrator
 */
class Util_Custom
{

    /**
     * 二维数组 把二层数据的key_val:下标做为上层数组的下标
     * 
     * @param integer $type
     * @param array $array
     * @param string $key_val
     * @return array | boolean
     */
    public static function setArrayIndex ($array, $type = 1, $key_val = 'id')
    {
        if (!empty($array)) {
            $arr = array();
            if ($type == 2) {
                foreach ($array AS $val){
                    $arr[$val] = $val;
                }
            } else {
                foreach ($array AS $value) {
                    $id = $value[$key_val];
                    $arr[$id] = $value;
                }
            }
            $array = NULL;
            return $arr;
        } else {
            return null;
        }
    }
    
    /**
     * 数组值组合
     * 在一个数组中array，通过键key_val 把把相应的值以delimiter的形式串起来
     * type == 2:以delimiter连接起来
     * @param $array 源数据
     * @param $keyVal 要组合的下标
     * @param $delimiter 以什么方式组合 默认是逗号
     */
    public static function arrayValueCombination($array,$key_val,$delimiter = ','){
        $result = null;
        if (!empty($array)){
            foreach ($array AS $value){
                $result[] = $value[$key_val];
            }
            $result = implode($delimiter, $result);
        }
        $array = NULL;
        return $result;
    }
    
}