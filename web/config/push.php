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

    'push_type' => 'jpush',//配置第三方推送

    /* 极光推送配置 */
    'jpush' => array(
        'push_url' => 'https://api.jpush.cn/v3/push',
        'device_url'=>'https://device.jpush.cn/v3/devices',
        'tags_url'=>'https://device.jpush.cn/v3/tags',
        'alias_url'=>'https://device.jpush.cn/v3/aliases',
       /*  'appKeys' => '5e87044ffcf05c66fbbfbbe6',
        'masterSecret' => '0756ad13cbfd2ef6ff3fae57', */
        'appKeys' => '74e7fde8e3f4e2753e69990d',                //appkeys
        'masterSecret' => '6abf34b43a4ecab000ff77c2',          //master Secret
        'apns_production'=>true //ios true:生产环境; false:开发环境
    ),

    /* 百度推送配置 */
    'baidupush' => array()
);

// End ^ Native EOL ^ UTF-8
