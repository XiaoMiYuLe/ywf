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

$config['site_name'] = '中金财富';
$config['store_url'] = 'http://test.ywf360.com'; // 本地地址
$config['store_url_login'] = 'http://test.ywf360.com'; // 登录地址
$config['static_url'] = '/static';
$config['upload_url'] = '/uploads'; // 跟配置的别名保持一致
$config['callback_url'] = 'http://120.27.166.159';// 回调urlhttp://120.27.166.159

/**
* 公共第三方JavaScript&CSS框架、插件等, 一般是按版本
* 目前demo版中，因cdn放在本站，所以static_cdn指向与static_url相同
*/
$config['static_cdn'] = '/static';
$config['upload_cdn'] = 'http://zjcfapi.ywf360.com'; // 图片服务器的域名
//支持银行
$config['bank_name'] = array('工商银行','农业银行','中国银行','建设银行','招商银行',
		'邮政储蓄银行','中信银行','光大银行','民生银行','平安银行','兴业银行','广发银行','浦发银行');
$config['bank_quota'] = array('单笔5万,单日5万,单月无限额',
						'单笔2万,单日5万,单月无限额',
						'单笔1万,单日1万,单月无限额',
						'单笔5万,单日100万,单月无限额',
						'单笔20万,单日100万,单月无限额',
						'单笔5万,单日5万,单月无限额',
						'单笔30万,单日100万,单月无限额',
						'单笔30万,单日100万,单月无限额',
						'单笔30万,单日100万,单月无限额',
						'单笔30万,单日100万,单月无限额',
						'单笔5万,单日5万,单月无限额',
						'单笔30万,单日50万,单月无限额',
						'单笔5000元,单日5000元,单月无限额');


return $config;

// End ^ LF ^ UTF-8
