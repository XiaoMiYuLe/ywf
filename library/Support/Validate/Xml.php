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

class Support_Validate_Xml
{
    /**
     * 判断数据是合法的 xml 格式数据: PHP >= 5.3
     * 
     * @param string $string
     * @return boolean
     */
    public static function isXml($string)
    {
        $xml_parser = xml_parser_create();
        if (xml_parse($xml_parser, $string, true)) {
            return true;
        }
        xml_parser_free($xml_parser);
        return false;
    }
    
    /**
     * 获取 xml 内容
     */
    public static function readXml($string)
    {
        $xml = simplexml_load_string($string);
        return $xml->__toString();
    }
}
