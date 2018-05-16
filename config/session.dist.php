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

$config['memcached'] = $config['memcache'] = array(
        'lifetime' => 7200, 
        'servers' => array(array('host' => 'localhost', 'port' => 11211, 'persistent' => true)), 
        'timeout' => 1000, 
        'retry_timeout' => 0, 
        'compression' => true, 
        'compatibility' => false, 
        'use_uuid_for_sid' => true, 
        'cookie_lifetime' => 0, 
        'cookie_path' => '/');

// 使用 php.ini 中的默认设置
$config['default'] = array(
        'cookie_lifetime' => 0,
        'cookie_path' => '/');

/**
 * Zeed_Controller_Action 允许指定 storager 为 default 以便使用 PHP.INI 中的设置
 * 如果需要使用 MEMCACHE 可以使用这个办法，在 PHP.INI 中设置
 *
 * 可设置值: default、memcache、memcached;
 */
$config['storager'] = 'default';

return $config;

// End ^ LF ^ UTF-8
