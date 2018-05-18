<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2012-5-7
 * @version SVN: $Id$
 */

/**
 * 商品操作相关的快速方法
 */
class Trend_Content
{
    public static function getPrimaryUrl()
    {
    }
    public static function getMultiUrl()
    {
    }
    
    /**
     * 获取商品的当前版本(含扩展属性和规格)完整信息
     *
     * @param integer $content_id
     * @return array null
     */
    public static function getCurrentVersionByContentid($content_id)
    {
        /* 获取商品主体信息 */
        $existMain = Goods_Model_Content::instance()->fetchByPK($content_id);
        if (! $existMain) {
            return null;
        }
        $row = $existMain[0];
        
        /* 获取商品详细信息，并和商品主体信息合并 */
        $content_detail = Goods_Model_Content_Detail::instance()->fetchByPK($content_id);
        if (! empty($content_detail)) {
            $row = array_merge($row, $content_detail[0]);
        }
        
        /* 获取商品属性信息 */
        $existProperty = Goods_Model_Property::instance()->fetchByFV('content_id', $content_id);
        
        if (count($existProperty)) {
            foreach ($existProperty as $_cp) {
                $_pName = PropertyHelper::id2name($_cp['property_id']);
                if (! is_null($_pName)) {
                    $row[$_pName] = Epv_Helper_Property::profilingIn($_pName, $_cp['value']);
                } else {
                    // 未找到字段信息
                    $row['p_' . $_cp['property_id']] = $_cp['value'];
                }
            }
        }
        
        return $row;
    }
    
    /**
     * 生成静态文件的URL路径
     *
     * @return Ambigous <string, multitype:string >
     */
    public static function generateFileURL($contentidOrContentRow, $categoryid = null, $returnArray = false)
    {
        return self::_generateFileURL($contentidOrContentRow, $categoryid, $returnArray);
    }
    
    /**
     * 生成内容的静态URL地址, 默认返回第一个分类的（如果有的话）
     *
     * @param integer|array $contentidOrContentRow
     * @param integer $categoryid
     * @param boolean $returnArray 是否返回数组，开启时返回多个
     * @return string
     */
    private static function _generateFileURL($contentidOrContentRow, $categoryid = null, $returnArray = false)
    {
        // Prepare content info
        if (is_array($contentidOrContentRow) && isset($contentidOrContentRow['contentid'])) {
            $contentRow = $contentidOrContentRow;
        } else {
            $contentRow = ContentHelper::getCurrentVersionByContentid($contentidOrContentRow);
        }
        if (empty($contentRow['ctime'])) {
            $contentRow['ctime'] = '0000-00-00 00:00:00';
        }
        
        // Prepare category info
        // @todo 处理dummy分类
        if (is_null($categoryid)) {
            if (! is_array($contentRow['category'])) {
                $tmp = explode(',', $contentRow['category']);
                $categoryid = $tmp[0]; // 使用默认分类(第一个)
            } else {
                $categoryid = array_shift($contentRow['category']);
            }
            if ($returnArray) {
                $return = array();
                foreach ($tmp as $categoryid) {
                    $return[] = self::_generateFileURL($contentRow, $categoryid, false);
                }
                return $return;
            }
        }
        $cInfo = Trend_Category::getCategoryByCategoryid($categoryid);
        if (is_array($cInfo)) {
            $archiveFormat = ($cInfo['archiveformat'] != '') ? $cInfo['archiveformat'] : Zeed_Config::loadGroup('html.archive_format');
            $filenameFormat = $cInfo['filenameformat'];
            $filename = Trend_Content::generateFilename($contentRow, $filenameFormat);
            $archiveFolder = Trend_Content::generateArchivePath($contentRow['ctime'], $archiveFormat);
            if ($cInfo['url'] != '') {
                $cInfo['url'] = (substr($cInfo['url'], '-1') == '/') ? $cInfo['url'] : $cInfo['url'] . '/';
                $return = $cInfo['url'] . $archiveFolder . $filename;
            } else {
                $return = Trend_Category::getCategoryUrlByCategoryid($categoryid) . $archiveFolder . $filename;
            }
        } else {
            // 无分类, 放在 uncategory 目录
            $config = Zeed_Config::loadGroup('html');
            $archiveFormat = $config['archive_format'];
            $filenameFormat = $config['filename_format'];
            $filename = Trend_Content::generateFilename($contentRow, $filenameFormat);
            $archiveFolder = Trend_Content::generateArchivePath($contentRow['ctime'], $archiveFormat);
            $return = $config['html_url'] . $config['uncategory_folder'] . '/' . $archiveFolder . $filename;
        }
        
        return $return;
    }
    
    /**
     * 在内容数组中，增加内容URL。
     *
     * @param array $cotentlist
     * @return array
     */
    public static function injectFileurlToContentList($cotentlist)
    {
        foreach ($cotentlist as $key => $row) {
            $row['url'] = Trend_Content::generateFileURL($row);
            $cotentlist[$key] = $row;
        }
        
        return $cotentlist;
    }
    
    /**
     * 根据参数自动产生不同格式文件名
     *
     * @param array $row
     * @param string $evalstring
     * @return string
     */
    public static function generateFilename($vars, $evalString)
    {
        if (is_array($vars)) {
            extract($vars);
        }
        
        if (preg_match_all('/(\w{1,})\(([a-zA-Z0-9 ,.$_"\'\x7f-\xff]{2,})\)/i', $evalString, $m, PREG_SET_ORDER)) {
            foreach ($m as $p) {
                $search[] = $p[0];
                if (! function_exists($p[1]) && file_exists(dirname(__FILE__) . '/file/' . $p[1] . '.php')) {
                    include_once ZEED_PATH_LIB . 'myfunctions/' . $p[1] . '.php';
                }
                
                if (function_exists($p[1])) {
                    $funRs = null;
                    eval('$funRs = ' . $p[0] . ';');
                    $repace[] = $funRs;
                } else {
                    $repace[] = $p[0];
                }
            }
            
            $file_name = str_replace($search, $repace, $evalString);
        } else {
            $file_name = $evalString;
        }
        @eval("\$file_name = \"$file_name\";");
        
        // 检查扩展名
        $file_ext = substr($file_name, strrpos($file_name, '.'));
        if ($file_ext == '') {
            $file_name = Zeed_Config::loadGroup('html.filename_extension');
        } elseif (! in_array($file_ext, Zeed_Config::loadGroup('html.filename_extension_allow'))) {
            $file_name = $file_name . Zeed_Config::loadGroup('html.filename_extension');
        }
        
        return Zeed_File::filename_security($file_name);
    }
    
    /**
     *
     * @param array $contentRow
     * @param string $evalString
     */
    public static function generateFilepath($vars, $evalString)
    {
        if (is_array($vars)) {
            extract($vars);
        }
        
        $file_path = $evalString;
        @eval("\$file_path = \"$file_path\";");
        $file_path = str_replace('\\', '/', $file_path);
        $tmp = explode('/', $file_path);
        $return = array();
        foreach ($tmp as $_tmp) {
            $_tmp = trim($_tmp);
            if ($_tmp !== '') {
                $return[] = $_tmp;
            }
        }
        $file_path = implode('/', $return);
        
        return $file_path;
    }
    
    /**
     * 计算归档目录
     *
     * @param integer|string $time
     * @param string $format
     * @return string
     */
    public static function generateArchivePath($time, $format = 'ym')
    {
        if (! is_int($time)) {
            $time = strtotime($time);
        }
        
        return date($format, $time) . '/';
    }
}

// End ^ Native EOL ^ UTF-8