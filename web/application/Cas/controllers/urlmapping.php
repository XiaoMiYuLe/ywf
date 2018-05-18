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
$config['store_url'] = 'http://112.64.173.178:20057'; // 本地地址
$config['store_url_login'] = 'http://112.64.173.178:20057'; // 登录地址
$config['static_url'] = '/static';
$config['upload_url'] = '/uploads'; // 跟配置的别名保持一致

/**
* 公共第三方JavaScript&CSS框架、插件等, 一般是按版本
* 目前demo版中，因cdn放在本站，所以static_cdn指向与static_url相同
*/
$config['static_cdn'] = '/static';
$config['upload_cdn'] = 'http://112.64.173.178:20057'; // 图片服务器的域名


//融宝支付支持银行
$config['bank_name'] = array('工商银行','农业银行','中国银行','建设银行',
		'邮政储蓄银行','中信银行','光大银行','民生银行','平安银行','兴业银行','招商银行','广发银行','华夏银行','浦发银行','交通银行');
$config['bank_quota'] = array('单笔5万，单日5万，单月无限额',
						'单笔5万，单日10万，单月无限额',
						'单笔1万，单日5万，单月无限额',
						'单笔5万，单日20万，单月20次',
						'单笔5000元，单日5000元，单月无限额',
						'单笔5万，单日20万，单月无限额',
						'单笔5万，单日20万，单月无限额',
						'单笔5万，单日20万，单月无限额',
						'单笔5万，单日20万，单月无限额',
						'单笔5万，单日5万，单月无限额',
						'单笔5万，单日20万，单月无限额',
						'单笔5万，单日20万，单月无限额',
						'单笔4999元，单日1.5万，单月3万',
						'单笔5万，单日20万，单月无限额',
						'单笔5万，单日5万，单月无限额');

//富友支付支持银行
$config['fuyoubank_name'] = array('工商银行','农业银行','中国银行','建设银行',
		'邮政储蓄银行','平安银行','光大银行','民生银行','广发银行','中信银行','兴业银行','华夏银行','交通银行','招商银行','上海浦东发展银行');
$config['fuyoubank_quota'] = array(
		'单笔5万，单日5万，单月20万',
		'单笔5万，单日10万，单月20万',
		'单笔5万，单日10万，单月20万',
		'单笔5万，单日10万，单月20万',
		'单笔5万，单日20万，单月20万',
		'单笔5万，单日20万，单月20万',
		'单笔5万，单日20万，单月20万',
		'单笔5万，单日20万，单月20万',
		'单笔5万，单日20万，单月20万',
		'单笔5万，单日20万，单月20万',
		'单笔5万，单日5万，单月20万',
		'单笔5万，单日20万，单月20万',
		'单笔5万，单日10万，单月20万',
		'单笔1000元，单日5万，单月20万',
		'单笔49999元，单日49999元，单月20万');

return $config;

// End ^ LF ^ UTF-8
