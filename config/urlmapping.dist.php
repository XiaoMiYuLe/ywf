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

$config['site_name'] = '基础开发包';
$config['store_url'] = 'http://yumzeed.lo.trac.cn'; // 本地地址
$config['store_url_login'] = 'http://yumzeed.lo.trac.cn'; // 登录地址
$config['static_url'] = '/static';
$config['upload_url'] = '/uploads'; // 跟配置的别名保持一致

/**
* 公共第三方JavaScript&CSS框架、插件等, 一般是按版本
* 目前demo版中，因cdn放在本站，所以static_cdn指向与static_url相同
*/
$config['static_cdn'] = '/static';
$config['upload_cdn'] = 'http://yumzeed.lo.trac.cn'; // 图片服务器的域名

return $config;

// End ^ LF ^ UTF-8