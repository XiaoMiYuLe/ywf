<?php
class CategoryHelper extends Trend_Content
{
    protected static $qgArray    = array();#[设置数组]
    protected static $qgTree     = array();#[格式化后的数组]
    protected static $space      = " 　　";#[不同级别的分格，默认是中文两个汉字]
    protected static $son_id     = array();
    protected static $parent_id  = array();
    protected static $pid        = 'parent_id';
    
    public static function cateTree($array=""){
        self::$qgArray = ($array && is_array($array)) ? $array : array();
    }
    
    public static function Tree($pkID,$parentid=0,$space=""){
        foreach(self::$qgArray AS $value){
            if($parentid == $value[self::$pid]){
                $value["space"] = $space;
                self::$qgTree[] = $value;
                self::Tree($pkID,$value[$pkID],$space.self::$space);
            }
        }
        return self::$qgTree;
    }
    
    /**
     * @param $array
     */
    public static function categoryFormat($pkID,$array) {
        $format = array();
        if (!empty($array)) {
            $p_id = 0;
            foreach ($array as $item) {
                if ($item[self::$pid] == 0) {
                    $p_id = $item[$pkID];
                    $format[$p_id] = $item;
                }else{
                    $format[$p_id]['item'][] = $item;
                }
            }
        }
        sort($format);
        return $format;
    }
    
}