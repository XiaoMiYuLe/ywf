<?php
/**
 * iNewS Project
 *
 * LICENSE
 *
 * http://www.inews.com.cn/license/inews
 *
 * @category   iNewS
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      May 13, 2010
 * @version    SVN: $Id: Cyrano $
 */

$config = array();

$config['smtp_host'] = 'smtp.qq.com';
$config['smtp_port'] = '25';
$config['smtp_user'] = '12345678@qq.com';
$config['smtp_pass'] = '123456';
$config['smtp_auth'] = 'login';

// 若无特殊要求，建议和 smtp_user 保持一致
$config['from_address'] = '12345678@qq.com'; 
$config['from_username'] = '蓝色互动';

/* 生成验证码的长度 */
$config['code_longth'] = '36';

/* 验证码的有效时长，单位：秒 */
$config['code_lifetime'] = 3600 * 1;

return $config;

// End ^ LF ^ encoding
