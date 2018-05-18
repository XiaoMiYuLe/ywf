<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category   Zeed
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      Jul 6, 2010
 * @version    SVN: $Id$
 */

/**
 * Memcached or Libmemcached
 */
$config['memcached'] = $config['libmemcached'] = array(
        'frontend' => 'Core', 
        'backend' => 'Memcached', 
        'frontendOption' => array('lifetime' => 7200, 'automatic_serialization' => true), 
        'backendOption' => array(
                'servers' => array(
                        array('host' => 'localhost', 'port' => 11211, 'persistent' => true)
                        ), 
                'compression' => false));

$config['file'] = array(
        'frontend' => 'Core', 
        'backend' => 'File', 
        'frontendOption' => array('lifetime' => 7200, 'automatic_serialization' => true), 
        'backendOption' => array(
                'hashed_directory_level' => 2, 
                'hashed_directory_perm' => 0777, 
                'file_name_prefix' => 'T', 
                'cache_dir' => ZEED_PATH_DATA . '/tmp/'));

/**
 * 这里主要是考虑开发跨平台兼容性配置.
 * 生产环境配置时应减少判断可提高效率.
 * 
 * memcached:
 *     $config['libmemcached']['backend'] = 'Libmemcached';
 *     $config['default'] = 'libmemcached';
 * memcache:
 *     $config['memcached']['backend'] = 'Memcached';
 *     $config['default'] = 'memcached';
 * file:
 *     $config['default'] = 'file';
 */
$config['default'] = 'file';

return $config;

// End ^ LF ^ UTF-8
