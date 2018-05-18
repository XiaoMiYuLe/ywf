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
 * @since      2009-8-12
 * @version    SVN: $Id: Date.php 4880 2010-01-21 03:19:40Z xsharp $
 */

class Zeed_Util_Date
{
    
    /**
     * 计算数据库中取得的时间与现在的差值
     * 格式: 10 days 14 hours 36 minutes 47 seconds
     * 
     * @param String $datetime Format: YYYY-MM-DD HH:MM:SS
     * @param Integer $time Unix timestamp (默认为当前Unix Timestamp)
     * @throws Zeed_Exception
     * @return String
     */
    public static function datetimespan($datetime, $time = '')
    {
        if (false == $timestamp = strtotime($datetime)) {
            throw new Zeed_Exception('datetime format error. @See <a href="http://php.net/strtotime" target="_blank">strtotime()</a>.');
        }
        if (! is_numeric($time)) {
            $time = time();
        }
        
        $span = self::timespan($timestamp, $time);
        
        return $span;
    }
    
    /**
     * Timespan
     *
     * Returns a span of seconds in this format:
     *	10 days 14 hours 36 minutes 47 seconds
     *
     * @access	public
     * @param integer $seconds a number of seconds
     * @param integer $time Unix timestamp (Default is: time())
     * @param integer $precision 精度
     * @return integer
     */
    public static function timespan($seconds = 1, $time = '')
    {
        if (! is_numeric($seconds)) {
            $seconds = 1;
        }
        
        if (! is_numeric($time)) {
            $time = time();
        }
        
        if ($time <= $seconds) {
            $seconds = 1;
        } else {
            $seconds = $time - $seconds;
        }
        
        $return = array();
        $years = floor($seconds / 31536000);
        if ($years > 0) {
            $return['year'] = $years;
        }
        
        $seconds -= $years * 31536000;
        $months = floor($seconds / 2628000);
        
        if ($years > 0 or $months > 0) {
            if ($months > 0) {
                $return['month'] = $months;
            }
            
            $seconds -= $months * 2628000;
        }
        
        $weeks = floor($seconds / 604800);
        
        if ($years > 0 or $months > 0 or $weeks > 0) {
            if ($weeks > 0) {
                $return['week'] = $weeks;
            }
            
            $seconds -= $weeks * 604800;
        }
        
        $days = floor($seconds / 86400);
        
        if ($months > 0 or $weeks > 0 or $days > 0) {
            if ($days > 0) {
                $return['day'] = $days;
            }
            
            $seconds -= $days * 86400;
        }
        
        $hours = floor($seconds / 3600);
        
        if ($days > 0 or $hours > 0) {
            if ($hours > 0) {
                $return['hour'] = $hours;
            }
            
            $seconds -= $hours * 3600;
        }
        
        $minutes = floor($seconds / 60);
        
        if ($days > 0 or $hours > 0 or $minutes > 0) {
            if ($minutes > 0) {
                $return['minute'] = $minutes;
            }
            
            $seconds -= $minutes * 60;
        }
        
        $return['second'] = $seconds;
        
        return $return;
    }
}

// End ^ LF ^ encoding
