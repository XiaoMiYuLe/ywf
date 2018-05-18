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
 * @package    Zeed_Cache
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      2010-6-30
 * @version    SVN: $Id: Cache.php 13322 2012-08-20 03:31:37Z xsharp $
 */

class Zeed_Cache extends Zend_Cache
{
    /**
     * Zeed Standard backends
     *
     * @var array
     */
    public static $zeedStandardBackends = array(
            'Memcached', 'Memcache', 'Array');

    /**
     * @var array
     */
    private static $_instances = null;

    /**
     * @param String $name
     * @throws Zend_Cache_Exception
     * @return Zend_Cache_Core|Zend_Cache_Frontend
     * @todo 多次条用 instace 可能返回不是之前的实例对象
     */
    public static function instance($name = null, $config = null)
    {
        if (Zend_Version::compareVersion('1.11.0') > 0) {
            throw new Zeed_Exception('Zeed_Cache need Zend Framework 1.11.0 or newer.');
        }
        
        if (isset(self::$_instances[$name])) {
            return self::$_instances[$name];
        }

        if (! is_null($name)) {
            if (! is_null($config)) {
                $config = array_merge(Zeed_Config::loadGroup('cache.' . $name), $config);
            } else {
                $config = Zeed_Config::loadGroup('cache.' . $name);
            }
        }

        if (! isset($config) || ! is_array($config)) {
            // default config
            $key = Zeed_Config::loadGroup('cache.default');
            $config = Zeed_Config::loadGroup('cache.' . $key);
        } else {
            $key = $name;
        }

        self::$_instances[$key] = self::factory($config['frontend'], $config['backend'], $config['frontendOption'], $config['backendOption']);

        return self::$_instances[$key];
    }

    /**
     * Factory 只是拷贝下 Zend_Cache::factory() 不做任何修改
     *
     * @param mixed  $frontend        frontend name (string) or Zend_Cache_Frontend_ object
     * @param mixed  $backend         backend name (string) or Zend_Cache_Backend_ object
     * @param array  $frontendOptions associative array of options for the corresponding frontend constructor
     * @param array  $backendOptions  associative array of options for the corresponding backend constructor
     * @param boolean $customFrontendNaming if true, the frontend argument is used as a complete class name ; if false, the frontend argument is used as the end of "Zend_Cache_Frontend_[...]" class name
     * @param boolean $customBackendNaming if true, the backend argument is used as a complete class name ; if false, the backend argument is used as the end of "Zend_Cache_Backend_[...]" class name
     * @param boolean $autoload if true, there will no require_once for backend and frontend (useful only for custom backends/frontends)
     * @throws Zend_Cache_Exception
     * @return Zend_Cache_Core|Zend_Cache_Frontend
     */
    public static function factory($frontend, $backend, $frontendOptions = array(), $backendOptions = array(), $customFrontendNaming = false, $customBackendNaming = false, $autoload = false)
    {
        if (is_string($backend)) {
            $backendObject = self::_makeBackend($backend, $backendOptions, $customBackendNaming, $autoload);
        } else {
            if ((is_object($backend)) && (in_array('Zend_Cache_Backend_Interface', class_implements($backend)))) {
                $backendObject = $backend;
            } else {
                self::throwException('backend must be a backend name (string) or an object which implements Zend_Cache_Backend_Interface');
            }
        }
        if (is_string($frontend)) {
            $frontendObject = self::_makeFrontend($frontend, $frontendOptions, $customFrontendNaming, $autoload);
        } else {
            if (is_object($frontend)) {
                $frontendObject = $frontend;
            } else {
                self::throwException('frontend must be a frontend name (string) or an object');
            }
        }
        $frontendObject->setBackend($backendObject);
        return $frontendObject;
    }

    /**
     * Frontend Constructor
     *
     * @param string  $backend
     * @param array   $backendOptions
     * @param boolean $customBackendNaming
     * @param boolean $autoload
     * @return Zend_Cache_Backend
     */
    public static function _makeBackend($backend, $backendOptions, $customBackendNaming = false, $autoload = false)
    {
        $backend = self::_normalizeName($backend);

        if (in_array($backend, self::$zeedStandardBackends) && class_exists('Memcached', false)) {
            $backendClass = 'Zeed_Cache_Backend_' . $backend;
        } else {
            return parent::_makeBackend($backend, $backendOptions, $customBackendNaming, $autoload);
        }

        return new $backendClass($backendOptions);
    }
}

// End ^ LF ^ UTF-8
