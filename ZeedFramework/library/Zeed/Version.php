<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zeed Framework.
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
 * @version    SVN: $Id: Util.php 8235 2010-11-09 03:10:03Z nroe $
 */

final class Zeed_Version
{
    /**
     * 
     * Zeed Framework 版本号标识
     */
    const VERSION = '0.1.0';

    /**
     * Zeed Framework 最后稳定版本号
     * 
     * @var string
     * @todo
     */
    protected static $_lastestVersion;

    /**
     * 将指定 Zeed Framework 版本号与当前 Zeed_Version::VERSION 做比较
     *
     * @param  string  $version  A version string (e.g. "0.7.1").
     * @return int -1 if the $version is older,
     *             0 if they are the same, and +1 if $version is newer.
     *
     */
    public static function compareVersion($version)
    {
        $version = strtolower($version);
        $version = preg_replace('/(\d)pr(\d?)/', '$1a$2', $version);
        $currentVersion = str_replace('zeed', '', strtolower(self::VERSION));
        
        return version_compare($version, $currentVersion);
    }

    /**
     * 判断当前运行 Zeed Framework 是否是旧版本
     * 通常用于守护进程等后台程序，解决由于守护进程等后台程序在 include() require() 时变量处于内存中，而无法得知当前版本状态
     * 
     * @return boolean 当当前运行程序版本低于加载版本时返回 true
     */
    public static function isOlder()
    {
        $zeedVersionFileContent = file_get_contents(__FILE__);
        
        if (false !== $zeedVersionFileContent) {
            if (preg_match("#const VERSION \= \'([0-9a-zA-Z\.]+)\'\;#ui", $zeedVersionFileContent, $matches)) {
                /**
                 * 当前 Zeed Framework 版本号，如果找不倒返回 '0.0.0'
                 * 
                 * @var $zeedVersion string
                 */
                $zeedVersion = isset($matches[1]) ? $matches[1] : '0.0.0';
                if (self::compareVersion($zeedVersion) <= 0 ) {
                    return false;
                }
            }
        }

        return true;
    }
}


// End ^ LF ^ UTF-8
