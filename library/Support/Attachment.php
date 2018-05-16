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

class Support_Attachment
{
    /**
     * 添加一个附件
     *
     * @param string $srcFile
     * @param string $suffix
     * @param integer $userid
     * @param string $title
     * @param string $description
     * @param string $lable
     */
    public static function upload($files)
    {
        $srcFile = $files['tmp_name'];
        $pos = strrpos($files['name'], '.');
        $suffix = $pos ? substr($files['name'], $pos + 1) : null;
        
        if (! file_exists($srcFile)) {
            throw new Zeed_Exception("Source file does not exist.");
        }
        
        // 计算或获取文件相关属性
        $mimeType = Zeed_File_MIMEType::mime($srcFile);
        $mimeType = strstr($mimeType, ';') ? substr($mimeType, 0, strrpos($mimeType, ';')) : $mimeType;
        $data = array();
        $data['mimetype'] = $mimeType;
        $data['suffix'] = $suffix ? $suffix : substr($mimeType, strpos($mimeType, '/') + 1);
        
        $temp_mimetype = explode('/', $mimeType);
        $data['mediatype'] = $temp_mimetype[0];
        if (strtolower($temp_mimetype[0]) == 'image') {
            $info = getimagesize($srcFile);
            $data['image_width'] = $info[0];
            $data['image_height'] = $info[1];
        }
        
        $data['hashcode'] = md5_file($srcFile);
        $data['filesize'] = filesize($srcFile);
        $data['filepath'] = Zeed_Storage::instance()->getUri($data['hashcode'], $data['suffix']);
        
        // 判断格式、大小等限制 - 暂不启用
        $config_attachment = Zeed_Config::loadGroup('attachment');
        if (! in_array($mimeType, $config_attachment['mimetype'])) {
            throw new Zeed_Exception("上传文件格式不正确，请检查后重新上传");
        }
//         if (($data['filesize'] / 1024) < $config_attachment['min_size'] || ($data['filesize'] / 1024) > $config_attachment['max_size']) {
//             throw new Zeed_Exception("上传文件大小超出限制，请确保上传文件大小介于 {$config_attachment['min_size']}kb 和 {$config_attachment['max_size']}kb 之间");
//         }
        
        // 文件存入FS
        Zeed_Storage::instance()->putFile($srcFile, $data['suffix'], $data['hashcode']);
        
        // 返回文件属性
        $config = Zeed_Storage::instance()->getConfig();
        $data['url'] = $config['url_prefix_b'] . $data['filepath'];
        return $data;
    }
    
    /**
     * 产生缩略图(JPEG格式)地址或文件类型图标地址 注意: 缩略图的扩展名可能不代表其真实的MIMEType
     *
     * @param boolean $filepath
     * @param string $mimetype 文件的MIMEType类型
     * @param string $thumbScheme 指定的已配置缩略图方案
     * @param string $urlPrefix 上传目录可访问地址
     * @return string 返回缩略图地址
     */
    public static function _generateThumbnailsUrl($filepath, $mimetype, $thumbScheme, $urlPrefix = null)
    {
        if (substr($mimetype, 0, 6) != 'image/') {
            $configIconsAttachment = Zeed_Config::loadGroup('icon.attachment');
            if (! isset($configIconsAttachment['list'][$mimetype])) {
                $thumbUrl = $configIconsAttachment['default'];
            } else {
                $thumbUrl = $configIconsAttachment['list'][$mimetype];
            }
            return $thumbUrl;
        }
    
        $thumbUrl = '';
        $suffix = substr($filepath, strrpos($filepath, '.'));
        $thumbUrl = str_replace($suffix, '_' . $thumbScheme . $suffix, $filepath);
    
        if (is_null($urlPrefix)) {
            $config = Zeed_Storage::instance()->getConfig();
            $thumbUrl = $config['url_thumb_mng_prefix'] . $thumbUrl;
        } else {
            $thumbUrl = $urlPrefix . $thumbUrl;
        }
    
        return $thumbUrl;
    }
    
    /**
     * 从存储系统中删除一个文件
     *
     * @param string $fileid
     * @param string $suffix
     * @return boolean
     */
    public static function eraseStoragedFile($fileid, $suffix)
    {
        return Zeed_Storage::instance()->unlink($fileid, $suffix);
    }
}
