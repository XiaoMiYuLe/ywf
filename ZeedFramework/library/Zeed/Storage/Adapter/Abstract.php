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
 * @since      2011-10-27
 * @version    SVN: $Id$
 */

/**
 * Abstract class for file storage (Save and Fetch)
 */
abstract class Zeed_Storage_Adapter_Abstract
{
    /**
     * User-provided configuration
     *
     * @var array
     */
    protected $_config = array();
    
    /**
     * Constructor.
     *
     * $config is an array of key/value pairs
     * containing configuration options.  These options are common to most adapters:
     *
     * root         => (string) The root dir name of storage
     *
     * @param  array $config An array
     * @throws Zend_Exception
     */
    public function __construct($config)
    {
        $this->_config = $config;
    }
    
    /**
     * Gets configuration options
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->_config;
    }
    
    /**
     * Returns the URI in storage.
     *
     * @param string $id
     * @param string $suffix
     * @return string
     */
    public function getUri($id, $suffix)
    {
        return $id . '.' . $suffix;
    }
    
    /**
     * Abstract Methods
     */
    
    /**
     * Gets content by id.
     * 
     * @param string $id
     * @param string $suffix
     * @return string
     */
    abstract public function get($id, $suffix);
    
    /**
     * Gets and echoes content by id.
     * 
     * @param string $id
     * @param string $suffix
     * @return string
     */
    abstract public function getOutput($id, $suffix, $headMime = false);
    
    /**
     * Saves content to storage.
     * 
     * @param string $data
     * @param string $suffix
     * @param string $id
     * @return boolean
     */
    abstract public function put($data, $suffix, $id = null);
    
    /**
     * Saves local file to storage.
     * 
     * @param string $srcFile
     * @param string $suffix
     * @param string $id
     * @return boolean
     */
    abstract public function putFile($srcFile, $suffix, $id = null);
    
    /**
     * Removes content from storage.
     * 
     * @param string $id
     * @param string $suffix
     * @return boolean
     */
    abstract public function unlink($id, $suffix);
}