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
 * @package    Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author     Zeed Team (http://blog.zeed.com.cn)
 * @since      Sep 29, 2010
 * @version    SVN: $Id: ShellAbstract.php 7516 2010-09-29 06:07:07Z nroe $
 */

abstract class Zeed_Controller_Action_Shell extends Zeed_Controller_Action
{
    /**
     * 是否跳过开启 XSS 过滤，如果设置为 true，那么该控制器下所有方法都将不启动 XSS 过滤
     * 如果设置为 array ，所有在 array 中的方法都将不启动 XSS 过滤
     *
     * @var boolean|array
     */
    protected $_skip_xss_clean = true;

    /**
     * 是否跳过开启 Session，如果设置为 true，那么该控制器下所有方法都将不启动 Session
     * 如果设置为 array ，所有在 array 中的方法都将不启动 Session
     *
     * @var boolean|array
     */
    protected $_skip_session_create = true;

    protected function _init()
    {
        if (! defined('ZEED_IN_CONSOLE')) {
            die('404 NOT FOUND, NOT IN CONSOLE MODE.');
        }

        /* Allow the script to hang around waiting for connections. */
        set_time_limit(0);

        /* Turn on implicit output flushing so we see what we're getting
         * as it comes in. */
        ob_implicit_flush();
    }
}

// End ^ Native EOL ^ encoding
