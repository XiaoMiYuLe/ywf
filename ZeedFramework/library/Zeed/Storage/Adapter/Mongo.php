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
 * @package    Zeed_Benchmark
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2011-12-27
 * @version    SVN: $Id$
 */

/**
 * File storage adapter for mongodb
 */
class Zeed_Storage_Adapter_Mongo extends Zeed_Storage_Adapter_Abstract
{
    /**
     * Returns the URI in storage.
     *
     * @param string $id
     * @param string $suffix
     * @return string
     */
    public function getUri($id, $suffix)
    {
        $length = intval(strlen($id) / 2);
        $uri = '/';
        for ($i = 0; ($i < $length && $i < 10); $i += 2) {
            $uri .= substr($id, $i, 2) . '/';
        }
        
        $uri .= substr($id, $i) . '.' . $suffix;
        return $uri;
    }
    
    /**
     * Gets content by id.
     * 
     * @param string $id
     * @param string $suffix
     * @return string
     */
    public function get($id, $suffix)
    {
        $mongo = Zeed_Db_Mongo::instance('mongodb', $this->_config['mongodb']);
        $dbname = $this->_config['mongodb']['db'];
        $db = $mongo->$dbname;
        $table = $db->getGridFS('attachment_binary');
        $row = $table->findOne(array('id' => $id, 'suffix' => $suffix));
        if ($row) {
            return $row->getBytes();
        }
        return null;
    }
    
    /**
     * Gets and echoes content by id.
     * 
     * @param string $id
     * @param string $suffix
     * @return string
     */
    public function getOutput($id, $suffix, $headMime = false)
    {
        $mongo = Zeed_Db_Mongo::instance('mongodb', $this->_config['mongodb']);
        $dbname = $this->_config['mongodb']['db'];
        $db = $mongo->$dbname;
        $table = $db->getGridFS('attachment_binary');
        $row = $table->findOne(array('id' => $id, 'suffix' => $suffix));
        if ($row) {
            if ($headMime && isset($row->file['mimetype']) && $row->file['mimetype']) {
                header("Content-Type: {$row->file['mimetype']}");
            }
            echo $row->getBytes();
            return true;
        }
        return false;
    }
    
    /**
     * Saves content to storage.
     * 
     * @param string $data
     * @param string $suffix
     * @param string $id
     * @return boolean
     */
    public function put($data, $suffix, $id = null)
    {
        if (is_null($id)) {
            $id = md5($data);
        }
        
        $mongo = Zeed_Db_Mongo::instance('mongodb', $this->_config['mongodb']);
        $dbname = $this->_config['mongodb']['db'];
        $db = $mongo->$dbname;
        $table = $db->getGridFS('attachment_binary');
        $row = $table->findOne(array('id' => $id, 'suffix' => $suffix));
        
        if ($row == null) {
            $table->storeBytes($data, array('id' => $id, 'suffix' => $suffix, 'filename' => $id . '.' . $suffix));
            $table->ensureIndex(array('id' => 1, 'suffix' => 1));
        }
        return $id;
    }
    
    /**
     * Saves local file to storage.
     * 
     * @param string $srcFile
     * @param string $id
     * @return boolean
     */
    public function putFile($srcFile, $suffix, $id = null)
    {
        if (is_null($id)) {
            $id = md5_file($srcFile);
        }
        
        $data = base64_encode(file_get_contents($srcFile));
        $mime = Zeed_File_MIMEType::mime($srcFile);
        
        $mongo = Zeed_Db_Mongo::instance('mongodb', $this->_config['mongodb']);
        $dbname = $this->_config['mongodb']['db'];
        $db = $mongo->$dbname;
        $table = $db->getGridFS('attachment_binary');
        $row = $table->findOne(array('id' => $id, 'suffix' => $suffix));
        if ($row == null) {
            $table->storeFile($srcFile, array('id' => $id, 'suffix' => $suffix, 'filename' => $id . '.' . $suffix, 'mimetype' => $mime));
            $table->ensureIndex(array('id' => 1, 'suffix' => 1));
        }
        return $id;
    }
    
    /**
     * Removes file from storage.
     * 
     * @param string $id
     * @param string $suffix
     * @return boolean
     */
    public function unlink($id, $suffix)
    {
        $mongo = Zeed_Db_Mongo::instance('mongodb', $this->_config['mongodb']);
        $dbname = $this->_config['mongodb']['db'];
        $db = $mongo->$dbname;
        $table = $db->getGridFS('attachment_binary');
        $row = $table->findOne(array('id' => $id, 'suffix' => $suffix));
        
        if ($row != null) {
            $table->remove(array('id' => $id, 'suffix' => $suffix));
        }
        return true;
    }
}