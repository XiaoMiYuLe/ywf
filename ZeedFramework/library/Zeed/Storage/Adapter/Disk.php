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
 * @since      2011-10-28
 * @version    SVN: $Id$
 */

/**
 * Normal disk file storage adapter for normal disk file system
 */
class Zeed_Storage_Adapter_Disk extends Zeed_Storage_Adapter_Abstract
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
        $uri = $this->getUri($id, $suffix);
        $filename = $this->_config['root'] . $uri;
        if (! file_exists($filename)) {
            return false;
        }
        return file_get_contents($filename);
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
        $uri = $this->getUri($id, $suffix);
        $filename = $this->_config['root'] . $uri;
        if (! file_exists($filename)) {
            return false;
        }
        
        readfile($filename);
        return true;
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
        
        $uri = $this->getUri($id, $suffix);
        $filename = $this->_config['root'] . $uri;
        if (! file_exists($filename)) {
            file_put_contents($filename, $data);
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
        
        $uri = $this->getUri($id, $suffix);
        $filename = $this->_config['root'] . $uri;
        Zeed_Util::mkpath(dirname($filename));
        if (! file_exists($filename)) {
            copy($srcFile, $filename);
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
        $uri = $this->getUri($id, $suffix);
        $filename = $this->_config['root'] . $uri;
        return unlink($filename);
    }
}