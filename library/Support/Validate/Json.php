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

class Support_Validate_Json
{
    /**
     * 判断数据是合法的 json 数据: PHP >= 5.3
     * 
     * @param string $string
     * @return boolean
     */
    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
