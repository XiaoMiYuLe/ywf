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

/* 默认采用的短信发送方式 */
$config['default'] = 'Sioo';

/* 短信发送方式列表 */
$config[] = array(
        /* 希奥 - 及时通短信 */
        'Sioo' => array(
                'uid' => 'test', // 用户编号
                'auth' => 'faea920f7412b5da7be0cf42b8c93759' // 签权验证（MD5(企业代码+用户密码)，32位加密小写）
        )
);

/* 生成短信验证码的长度 */
$config['code_longth'] = '6';

/* 验证码的有效时长，单位：秒 */
$config['code_lifetime'] = 120 * 1;

return $config;

// End ^ LF ^ UTF-8
