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
 * @since      2010-12-9
 * @version    SVN: $Id$
 */

/**
 * 缓存到数组
 *
 * @package Cache
 * @subpackage Backend
 */
class Zeed_Cache_Backend_Array extends Zend_Cache_Backend implements Zend_Cache_Backend_ExtendedInterface
{
    /**
     * Array store data
     * 
     * @var $_cache array
     */
    protected $_cache = array();
    
    /**
     * Array of metadatas (each item is an associative array)
     *
     * @var array
     */
    protected $_metadatasArray = array();
    
    /**
     * Test if a cache is available for the given id and (if yes) return it (false else)
     *
     * @param string $id cache id
     * @param boolean $doNotTestCacheValidity if set to true, the cache validity won't be tested
     * @return string|false cached datas
     */
    public function load($id, $doNotTestCacheValidity = false)
    {
        if (! ($this->_test($id, $doNotTestCacheValidity))) {
            // The cache is not hit !
            return false;
        }
        
        if (isset($this->_cache[$id])) {
            return $this->_cache[$id];
        }
        
        return false;
    }
    
    /**
     * Test if a cache is available or not (for the given id)
     *
     * @param string $id cache id
     * @return mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    public function test($id)
    {
        return $this->_test($id, false);
    }
    
    /**
     * Save some string datas into a cache record
     *
     * Note : $data is always "string" (serialization is done by the
     * core not by the backend)
     *
     * @param  string $data             Datas to cache
     * @param  string $id               Cache id
     * @param  array  $tags             Array of strings, the cache record will be tagged by each string entry
     * @param  int    $specificLifetime If != false, set a specific lifetime for this cache record (null => infinite lifetime)
     * @return boolean true if no problem
     */
    public function save($data, $id, $tags = array(), $specificLifetime = false)
    {
        $metadatas = array(
                'mtime' => time(),
                'expire' => $this->_expireTime($this->getLifetime($specificLifetime)),
                'tags' => $tags);
        
        $this->_metadatasArray[$id] = $metadatas;
        $this->_cache[$id] = $data;
        
        return true;
    }
    
    /**
     * Remove a cache record
     *
     * @param  string $id cache id
     * @return boolean true if no problem
     */
    public function remove($id)
    {
        if (isset($this->_cache[$id])) {
            unset($this->_cache[$id]);
        }
        
        return true;
    }
    
    /**
     * Clean some cache records
     *
     * Available modes are :
     * 'all' (default)  => remove all cache entries ($tags is not used)
     * 'old'            => remove too old cache entries ($tags is not used)
     * 'matchingTag'    => remove cache entries matching all given tags
     * ($tags can be an array of strings or a single string)
     * 'notMatchingTag' => remove cache entries not matching one of the given tags
     * ($tags can be an array of strings or a single string)
     * 'matchingAnyTag' => remove cache entries matching any given tags
     * ($tags can be an array of strings or a single string)
     *
     * @param string $mode clean mode
     * @param tags array $tags array of tags
     * @return boolean true if no problem
     */
    public function clean($mode = Zend_Cache::CLEANING_MODE_ALL, $tags = array())
    {
        // We use this protected method to hide the recursive stuff
        $this->_cache = array();
        return true;
    }
    
    /**
     * Return an array of stored cache ids
     *
     * @return array array of stored cache ids (string)
     */
    public function getIds()
    {
        return array_keys($this->_cache);
    }
    
    /**
     * Return an array of stored tags
     *
     * @return array array of stored tags (string)
     */
    public function getTags()
    {
        return array();
    }
    
    /**
     * Return an array of stored cache ids which match given tags
     *
     * In case of multiple tags, a logical AND is made between tags
     *
     * @param array $tags array of tags
     * @return array array of matching cache ids (string)
     */
    public function getIdsMatchingTags($tags = array())
    {
        return array();
    }
    
    /**
     * Return an array of stored cache ids which don't match given tags
     *
     * In case of multiple tags, a logical OR is made between tags
     *
     * @param array $tags array of tags
     * @return array array of not matching cache ids (string)
     */
    public function getIdsNotMatchingTags($tags = array())
    {
        return array();
    }
    
    /**
     * Return an array of stored cache ids which match any given tags
     *
     * In case of multiple tags, a logical AND is made between tags
     *
     * @param array $tags array of tags
     * @return array array of any matching cache ids (string)
     */
    public function getIdsMatchingAnyTags($tags = array())
    {
        return array();
    }
    
    /**
     * Return the filling percentage of the backend storage
     *
     * @throws Zend_Cache_Exception
     * @return int integer between 0 and 100
     */
    public function getFillingPercentage()
    {
        return 100;
    }
    
    /**
     * Return an array of metadatas for the given cache id
     *
     * The array must include these keys :
     * - expire : the expire timestamp
     * - tags : a string array of tags
     * - mtime : timestamp of last modification time
     *
     * @param string $id cache id
     * @return array array of metadatas (false if the cache id is not found)
     */
    public function getMetadatas($id)
    {
        $metadatas = $this->_getMetadatas($id);
        if (! $metadatas) {
            return false;
        }
        if (time() > $metadatas['expire']) {
            return false;
        }
        return array(
                'expire' => $metadatas['expire'],
                'tags' => $metadatas['tags'],
                'mtime' => $metadatas['mtime']);
    }
    
    /**
     * Give (if possible) an extra lifetime to the given cache id
     *
     * @param string $id cache id
     * @param int $extraLifetime
     * @return boolean true if ok
     */
    public function touch($id, $extraLifetime)
    {
        $metadatas = $this->_getMetadatas($id);
        if (! $metadatas) {
            return false;
        }
        
        if (time() > $metadatas['expire']) {
            return false;
        }
        
        $newMetadatas = array(
                'mtime' => time(),
                'expire' => $metadatas['expire'] + $extraLifetime,
                'tags' => array());
        
        $this->_metadatasArray[$id] = $newMetadatas;
        return true;
    }
    
    /**
     * Return an associative array of capabilities (booleans) of the backend
     *
     * The array must include these keys :
     * - automatic_cleaning (is automating cleaning necessary)
     * - tags (are tags supported)
     * - expired_read (is it possible to read expired cache records
     * (for doNotTestCacheValidity option for example))
     * - priority does the backend deal with priority when saving
     * - infinite_lifetime (is infinite lifetime can work with this backend)
     * - get_list (is it possible to get the list of cache ids and the complete list of tags)
     *
     * @return array associative of with capabilities
     */
    public function getCapabilities()
    {
        return array(
                'automatic_cleaning' => false,
                'tags' => false,
                'expired_read' => true,
                'priority' => false,
                'infinite_lifetime' => true,
                'get_list' => true);
    }
    
    /**
     * Get a metadatas record
     *
     * @param  string $id  Cache id
     * @return array|false Associative array of metadatas
     */
    protected function _getMetadatas($id)
    {
        if (isset($this->_metadatasArray[$id])) {
            return $this->_metadatasArray[$id];
        }
        
        return false;
    }
    
    /**
     * Compute & return the expire time
     *
     * @return int expire time (unix timestamp)
     */
    protected function _expireTime($lifetime)
    {
        if ($lifetime === null) {
            return 9999999999;
        }
        return time() + $lifetime;
    }
    
    /**
     * Test if the given cache id is available (and still valid as a cache record)
     *
     * @param  string  $id                     Cache id
     * @param  boolean $doNotTestCacheValidity If set to true, the cache validity won't be tested
     * @return boolean|mixed false (a cache is not available) or "last modified" timestamp (int) of the available cache record
     */
    protected function _test($id, $doNotTestCacheValidity)
    {
        $metadatas = $this->_getMetadatas($id);
        
        if (! $metadatas) {
            return false;
        }
        
        if ($doNotTestCacheValidity || (time() <= $metadatas['expire'])) {
            return $metadatas['mtime'];
        }
        
        return false;
    }
}

// End ^ Native EOL ^ encoding
