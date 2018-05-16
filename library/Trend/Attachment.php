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

class Trend_Attachment
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
    public static function add($srcFile, $suffix = null, $userid = 0, $title = '', $description = '', $label = '')
    {
        if (! file_exists($srcFile)) {
            throw new Zeed_Exception("Source file does not exist.");
        }
        
        /* 计算或获取文件相关属性 */
        $mimeType = Zeed_File_MIMEType::mime($srcFile);
        $mimeType = strstr($mimeType, ';') ? substr($mimeType, 0, strrpos($mimeType, ';')) : $mimeType;
        $data = array();
        $data['userid'] = $userid;
        $data['title'] = $title;
        $data['description'] = $description;
        $data['label'] = $label;
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
        
        $data['status'] = 1;
        $data['rev'] = 1;
        $data['ctime'] = $data['mtime'] = date(DATETIME_FORMAT);
        
        /* 判断格式、大小等限制 - 暂不启用 */
        $config_attachment = Zeed_Config::loadGroup('attachment');
        if (! in_array($mimeType, $config_attachment['mimetype'])) {
            throw new Zeed_Exception("上传文件格式不正确，请检查后重新上传");
        }
        if (($data['filesize'] / 1024) < $config_attachment['min_size'] || ($data['filesize'] / 1024) > $config_attachment['max_size']) {
            throw new Zeed_Exception("上传文件大小超出限制，请确保上传文件大小介于 {$config_attachment['min_size']}kb 和 {$config_attachment['max_size']}kb 之间");
        }
        
        /* 读取配置信息 */
        $config = Zeed_Storage::instance()->getConfig();
        
        /* 检查附件如果存在，则直接返回该附件信息 */
        $attach = Trend_Model_Attachment::instance()->fetchByHashcodeAndUserid($data['hashcode'], $userid);
        if (is_array($attach) && count($attach) > 0) {
            $attach['url'] = $config['url_prefix_b'] . $attach['filepath'];
            return $attach;
        }
        
        /* 存入attachment表 */
        $data['attachmentid'] = Trend_Model_Attachment::instance()->addForEntity($data);
        
        /* 文件存入FS */
        Zeed_Storage::instance()->putFile($srcFile, $data['suffix'], $data['hashcode']);
        
        /* 返回文件属性 */
        $data['url'] = $config['url_prefix_b'] . $data['filepath'];
        return $data;
    }
    
    /**
     * 更新附件信息
     *
     * @param integer $attachid
     * @param string $srcFile
     * @param string $suffix
     * @param integer $userid
     * @param string $title
     * @param string $description
     * @param string $lable
     */
    public static function update($attachid, $srcFile = null, $suffix = null, $userid = 0, $title = '', $description = '', $label = '')
    {
        $attach = Trend_Model_Attachment::instance()->fetchByAttchmentid($attachid);
        if (empty($attach)) {
            throw new Zeed_Exception("Attachment with id {$attachid} does not exist.");
        }
        $attach = $attach[0];
        $data = array();
        // 判断是否需要更新
        if ($srcFile && file_exists($srcFile)) {
            $hashcode = md5_file($srcFile);
            if ($hashcode != $attach['hashcode']) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($srcFile);
                $mimeType = strstr($mimeType, ';') ? substr($mimeType, 0, strrpos($mimeType, ';')) : $mimeType;
                
                $data['mimetype'] = $mimeType;
                $data['suffix'] = $suffix ? $suffix : substr($mimeType, strpos($mimeType, '/') + 1);
                
                $temp_mimetype = explode('/', $mimeType);
                $data['mediatype'] = $temp_mimetype[0];
                if (strtolower($temp_mimetype[0]) == 'image') {
                    $info = getimagesize($srcFile);
                    $data['image_width'] = $info[0];
                    $data['image_height'] = $info[1];
                }
                $data['hashcode'] = $hashcode;
                $data['filesize'] = filesize($srcFile);
                $data['filepath'] = Zeed_Storage::instance()->getUri($data['hashcode'], $data['suffix']);
            }
        }
        if (! is_null($userid) && $userid != $attach['userid']) {
            $data['userid'] = $userid;
        }
        if (! is_null($title) && $title != $attach['title']) {
            $data['title'] = $title;
        }
        if (! is_null($description) && $description != $attach['description']) {
            $data['description'] = $description;
        }
        if (! is_null($label) && $label != $attach['label']) {
            $data['label'] = $label;
        }
        
        if (! empty($data)) {
            // 更新数据库
            $data['rev'] = $attach['rev'] + 1;
            $history = array(
                    'attachmentid' => $attach['attachmentid'], 
                    'rev' => $attach['rev'], 
                    'data' => serialize($attach));
            Trend_Model_Attachment_History::instance()->addHistory($history);
            Trend_Model_Attachment::instance()->update($data, array('attachmentid = ?' => $attachid));
            
            // 更新文件
            if (isset($data['hashcode'])) {
                Zeed_Storage::instance()->putFile($srcFile, $data['suffix'], $data['hashcode']);
            }
        }
        
        $data = array_merge($attach, $data);
        $config = Zeed_Storage::instance()->getConfig();
        $data['url'] = $config['url_prefix'] . $data['filepath'];
        return $data;
    }
    
    /**
     * 标记附件为删除状态，不是真的删除
     *
     * @param integer $attachid
     * @return boolean
     */
    public static function delete($attachid, $permanentlyErase = false)
    {
        $attachidArr = array();
        if (is_array($attachid)) {
            $attachidArr = $attachid;
        } elseif (is_numeric($attachid)) {
            $attachidArr[] = $attachid;
        } elseif (is_string($attachid) && strpos($attachid, ',')) {
            $attachidArr = explode(",", $attachid);
        }
        
        if ($permanentlyErase) {
            // 永久删除: 删除链接, 判断性删除文件存储
            $rows = Trend_Model_Attachment::instance()->fetchByAttchmentid($attachidArr);
            
            // 保险起见, 逐个删除
            foreach ($rows as $row) {
                $hashcodeCount = Trend_Model_Attachment::instance()->fetchCountByHashcode($row['hashcode']);
                if ($hashcodeCount == 1) { 
                    // 仅有一个链接(应该是当前待删的附件), 删除
                    self::eraseStoragedFile($row['hashcode'], $row['suffix']);
                }
                
                // 删链接
                self::deleteAttachmentByAttachmentid($row['attachmentid']);
            }
        
        } else {
            // 标记删除
            Trend_Model_Attachment::instance()->batchUpdateStatusByAttachmentids($attachidArr, - 1);
        }
        
        return true;
    }
    
    /**
     * 从存储系统中删除一个文件
     *
     * @param string $fileid
     * @param string $suffix
     * @return boolean
     */
    private static function eraseStoragedFile($fileid, $suffix)
    {
        return Zeed_Storage::instance()->unlink($fileid, $suffix);
    }
    
    /**
     * 删除一个附件
     *
     * 同时需要删除附件的相关引用, 如: content_attachment
     * @param integer $attachmentid
     * @return boolean
     */
    private static function deleteAttachmentByAttachmentid($attachmentid)
    {
        if (Trend_Model_Attachment::instance()->deleteByAttachmentid($attachmentid)) {
            // 清理 Content_Attachment 中关于删除附件的引用
            Trend_Model_Content_Attachment::instance()->deleteByAttachmentid($attachmentid);
        }
        
        return true;
    }
    
    /**
     * 还原附件，即将附件状态修改为正常状态
     *
     * @param integer|string|array $attachid
     * @return boolean
     */
    public static function restoreAttachmentByAttachmentid($attachid)
    {
        $attachidArr = array();
        if (is_array($attachid)) {
            $attachidArr = $attachid;
        } elseif (is_numeric($attachid)) {
            $attachidArr[] = $attachid;
        } elseif (is_string($attachid) && strpos($attachid, ',')) {
            $attachidArr = explode(",", $attachid);
        }
        
        Trend_Model_Attachment::instance()->batchUpdateStatusByAttachmentids($attachidArr, 1);
        
        return true;
    }
    
}
