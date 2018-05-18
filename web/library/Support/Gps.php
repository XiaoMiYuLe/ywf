<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_Benchmark
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2011-10-31
 * @version SVN: $Id$
 */

class Support_Gps
{
    /**
     * 计算两个经纬度之间的距离（单位：米）
     * 
     * @param float $lng_from 经度起点
     * @param float $lat_from 纬度起点
     * @param float $lng_to 经度终点
     * @param float $lat_to 纬度终点
     * @return float
     */
    public static function distance($lng_from, $lat_from, $lng_to, $lat_to)
    {
        $earth_radius = 6378.137; // 地球半径
        
        /* 将角度转为狐度 */
        $rad_lat_from = deg2rad($lat_from);
        $rad_lat_to = deg2rad($lat_to);
        $rad_lng_from = deg2rad($lng_from);
        $rad_lng_to = deg2rad($lng_to);
        
        $a = $rad_lat_from - $rad_lat_to; // 两纬度之差，纬度<90
        $b = $rad_lng_from - $rad_lng_to; // 两经度之差，经度<180
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($rad_lat_from) * cos($rad_lat_to) * pow(sin($b / 2), 2))) * $earth_radius;
        
        return round($s * 1000, 3);
    }
    
    /**
     * 查找一定范围内的最大、最小经纬度
     * 
     * @param float $lng 经度
     * @param float $lat 纬度
     * @param float $raidus 查找半径（单位：米）
     * @return array
     */
    public static function around($lng, $lat, $raidus)
    {
        $pi = 3.14159265; // 圆周率
        
        $degree = (24901 * 1609) / 360.0;
        $dpm_lat = 1 / $degree;
        $radius_lat = $dpm_lat * $raidus;
        
        $data['lat_min'] = $lat - $radius_lat;
        $data['lat_max'] = $lat + $radius_lat;
        
        $mpd_lng = $degree * cos($lat * ($pi / 180));
        $dpm_lng = 1 / $mpd_lng;
        $radius_lng = $dpm_lng * $raidus;
        
        $data['lng_min'] = $lng - $radius_lng;
        $data['lng_max'] = $lng + $radius_lng;
        
        return $data;
    }
}
