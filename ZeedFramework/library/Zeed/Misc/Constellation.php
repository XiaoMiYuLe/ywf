<?php
/**
 * Playcool Project
 * 
 * LICENSE
 * 
 * http://www.playcool.com/license/ice
 * 
 * @category   ICE
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Mar 18, 2009
 * @version    SVN: $Id: Constellation.php 159 2009-05-15 07:50:10Z xsharp $
 */

class Zeed_Misc_Constellation
{
    private $_horoscopeName = array(
            '',
            '白羊座',
            '金牛座',
            '双子座',
            '巨蟹座',
            '狮子座',
            '处女座',
            '天秤座',
            '天蝎座',
            '射手座',
            '摩羯座',
            '水瓶座',
            '双鱼座');
    
    /**
     * 日期转换成星座索引
     * 
     * @param $date
     * @return Integer
     */
    public static function dateToConstellation($date)
    {
        $monthDay = date('md', strtotime($date));
        
        if ($monthDay != "0000") {
            switch ($monthDay) {
                case $monthDay >= "0321" && $monthDay <= "0419" :
                    $horoscope = 1;
                    break;
                case $monthDay >= "0420" && $monthDay <= "0520" :
                    $horoscope = 2;
                    break;
                case $monthDay >= "0521" && $monthDay <= "0620" :
                    $horoscope = 3;
                    break;
                case $monthDay >= "0621" && $monthDay <= "0721" :
                    $horoscope = 4;
                    break;
                case $monthDay >= "0722" && $monthDay <= "0822" :
                    $horoscope = 5;
                    break;
                case $monthDay >= "0823" && $monthDay <= "0922" :
                    $horoscope = 6;
                    break;
                case $monthDay >= "0923" && $monthDay <= "1022" :
                    $horoscope = 7;
                    break;
                case $monthDay >= "1023" && $monthDay <= "1121" :
                    $horoscope = 8;
                    break;
                case $monthDay >= "1122" && $monthDay <= "1221" :
                    $horoscope = 9;
                    break;
                case $monthDay >= "0120" && $monthDay <= "0218" :
                    $horoscope = 11;
                    break;
                case $monthDay >= "0219" && $monthDay <= "0320" :
                    $horoscope = 12;
                    break;
                default :
                    $horoscope = 10;
            }
        } else {
            $horoscope = 0;
        }
        
        return $horoscope;
    }
    
    /**
     * 根据索引星座中文名
     * 
     * @param $index
     * @return String 星座中文名
     */
    public static function getConstellation($index)
    {
        $index = ( int ) $index;
        
        return (0 < $index && $index < 13) ? self::$_horoscopeName[$index] : '';
    }
}

// End ^ LF ^ UTF-8
