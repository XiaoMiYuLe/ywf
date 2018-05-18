<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      May 11, 2010
 * @version    SVN: $Id$
 */

class Zeed_File_Util
{
    const FILE_LIST_FILES = 1; 
    const FILE_LIST_DIRS = 2;
    const FILE_LIST_DOTS = 4;
    const FILE_LIST_ALL = 7;
    
    const FILE_SORT_NONE = 0;
    const FILE_SORT_REVERSE = 1;
    const FILE_SORT_NAME = 2;
    const FILE_SORT_SIZE = 4;
    const FILE_SORT_DATE = 8;
    const FILE_SORT_RANDOM = 16;

    /**
     * 获取目录列表
     *
     * The final argument, $cb, is a callback that either evaluates to true or
     * false and performs a filter operation, or it can also modify the
     * directory/file names returned.  To achieve the latter effect use as
     * follows:
     *
     * <code>
     * <?php
     * function uc(&$filename) {
     *     $filename = strtoupper($filename);
     *     return true;
     * }
     * $entries = File_Util::listDir('.', FILE_LIST_ALL, Zeed_File_Util::FILE_SORT_NONE, 'uc');
     * foreach ($entries as $e) {
     *     echo $e->name, "\n";
     * }
     * ?>
     * </code>
     *
     * @static
     * @access  public
     * @return  array
     * @param   string  $path
     * @param   int     $list
     * @param   int     $sort
     * @param   mixed   $cb
     */
    public static function listDir($path, $list = Zeed_File_Util::FILE_LIST_ALL, $sort = Zeed_File_Util::FILE_SORT_NONE, $cb = null)
    {
        if (!strlen($path) || !is_dir($path)) {
            return null;
        }

        $entries = array();
        for ($dir = dir($path); false !== $entry = $dir->read(); ) {
            if ($list & Zeed_File_Util::FILE_LIST_DOTS || $entry{0} !== '.') {
                $isRef = ($entry === '.' || $entry === '..');
                $isDir = $isRef || is_dir($path .'/'. $entry);
                if (    ((!$isDir && $list & Zeed_File_Util::FILE_LIST_FILES)   ||
                         ($isDir  && $list & Zeed_File_Util::FILE_LIST_DIRS))   &&
                        (!is_callable($cb) ||
                            call_user_func_array($cb, array(&$entry)))) {
                    $entries[] = (object) array(
                        'name'  => $entry,
                        'size'  => $isDir ? null : filesize($path .'/'. $entry),
                        'date'  => filemtime($path .'/'. $entry),
                    );
                }
            }
        }
        $dir->close();

        if ($sort) {
            $entries = Zeed_File_Util::sortFiles($entries, $sort);
        }

        return $entries;
    }
    

    /**
     * 文件排序
     *
     * @return  array
     * @param   array   $files
     * @param   int     $sort
     */
    public static function sortFiles($files, $sort)
    {
        if (!$files) {
            return array();
        }

        if (!$sort) {
            return $files;
        }

        if ($sort === 1) {
            return array_reverse($files);
        }

        if ($sort & Zeed_File_Util::FILE_SORT_RANDOM) {
            shuffle($files);
            return $files;
        }

        $names = array();
        $sizes = array();
        $dates = array();

        if ($sort & Zeed_File_Util::FILE_SORT_NAME) {
            $r = &$names;
        } elseif ($sort & Zeed_File_Util::FILE_SORT_DATE) {
            $r = &$dates;
        } elseif ($sort & Zeed_File_Util::FILE_SORT_SIZE) {
            $r = &$sizes;
        } else {
            asort($files, SORT_REGULAR);
            return $files;
        }

        $sortFlags = array(
            Zeed_File_Util::FILE_SORT_NAME => SORT_STRING,
            Zeed_File_Util::FILE_SORT_DATE => SORT_NUMERIC,
            Zeed_File_Util::FILE_SORT_SIZE => SORT_NUMERIC,
        );

        foreach ($files as $file) {
            $names[] = $file->name;
            $sizes[] = $file->size;
            $dates[] = $file->date;
        }

        if ($sort & Zeed_File_Util::FILE_SORT_REVERSE) {
            arsort($r, $sortFlags[$sort & ~1]);
        } else {
            asort($r, $sortFlags[$sort]);
        }

        $result = array();
        foreach ($r as $i => $f) {
            $result[] = $files[$i];
        }

        return $result;
    }
}

// End ^ LF ^ encoding
