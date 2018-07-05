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
 * 全局数据表前缀
 */
defined('ZEED_DB_TABLEPREFIX') || define('ZEED_DB_TABLEPREFIX', '');

/**
 * 数据库配置信息
 * 
 * default - 默认数据库
 */
return array(
        'default' => array(
                'adapter' => 'PDO_MYSQL', 
                'host' => 'rdsbib620f206m7h9jox.mysql.rds.aliyuncs.com',
//                'host' => '192.168.1.195',
                'username' => 'ywf',
                'password' => 'ywf360sql',
//                'username' => 'root',
//                'password' => '1qaz)OKM',
                'dbname' => 'zjcf', 
                'prefix' => '', 
                'charset' => 'utf8', 
                'profiler' => 1
        ), 

        /** 
         * 这里仅仅是配置, 不兼容Zend_Db_Adapter
         */
        'redis' => array(
                'host' => 'localhost',
                'port' => '6379',
                'db' => 0,
                // 生命周期，0 为无限制
                'expire' => 0 
        )
);

// End ^ Native EOL ^ UTF-8
