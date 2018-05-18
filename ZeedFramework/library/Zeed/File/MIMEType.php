<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 * 
 * BTS - Billing Transaction Service
 * CAS - Central Authentication Service
 * 
 * LICENSE
 * http://www.zeed.com.cn/license/
 * 
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      Sep 28, 2010
 * @version    SVN: $Id: MIMEType.php 12754 2012-03-21 10:27:00Z xsharp $
 */

class Zeed_File_MIMEType
{
    
    /**
     * Return information about a file
     *
     * @param String $file
     * @return String
     */
    public static function mime($file)
    {
        if (class_exists('finfo')) {
            $finfo = new finfo(FILEINFO_MIME);
            return $finfo->file($file);
        } else {
            if (DIRECTORY_SEPARATOR == '/') {
                // *nix, use system's magic/magic.mgc/magic.mime
                $fileBin = '/usr/bin/file';
            } else {
                // win
                $fileBin = ZEED_PAHT_BIN . 'win/file.exe';
                $magicPath = ZEED_PAHT_BIN . '/win/magic';
            }
            $cmd = $fileBin . ' -i -b -m ' . $magicPath . ' ' . $file;
            $info = array();
            exec($cmd, $info);
            
            return $info[0];
        }
        
        die('Get file\'s MimeType Fail.');
    }
    
    public static function mimeBuffer($buffer)
    {
        $finfo = new finfo(FILEINFO_MIME);
        return $finfo->buffer($buffer);
    }

}

// End ^ Native EOL ^ encoding
