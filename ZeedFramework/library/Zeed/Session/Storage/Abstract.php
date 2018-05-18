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
 * @since      2010-7-2
 * @version    SVN: $Id$
 */

abstract class Zeed_Session_Storage_Abstract {
    /**
     * Session lifetime
     *
     * @var int
     */
    protected $_lifetime = false;

    /**
     * Whether or not the lifetime of an existing session should be overridden
     *
     * @var boolean
     */
    protected $_overrideLifetime = false;

    /**
     * Session 保存路径
     * 该值从 session_set_save_handler.Open 中传递过来
     *
     * @var string
     */
    protected $_sessionSavePath;

    /**
     * Session 名称
     * 该值从 session_set_save_handler.Open 中传递过来
     *
     * @var string
     */
    protected $_sessionName;

    /**
     * 设置 Session 生命周期，如果设置了一个无效值那么使用系统配置 PHP.INI
     *
     * @param integer $lifetime
     * @return Zend_Session_SaveHandler_DbTable
     */
    public function setLifetime($lifetime)
    {
        if (empty($lifetime) || $lifetime < 0) {
            $this->_lifetime = (int) ini_get('session.gc_maxlifetime');
        } else {
            $this->_lifetime = (int) $lifetime;
        }
    }

    /**
     * 获取 Session 生命周期
     *
     * @return integer
     */
    public function getLifetime()
    {
        return $this->_lifetime;
    }

    /**
     * 设置 Session 在更新后是否同时更新时间
     * 如果设置为否，那么 Session 的生命不会得到延长
     *
     * @param boolean $overrideLifetime
     */
    public function setOverrideLifetime($overrideLifetime)
    {
        $this->_overrideLifetime = (boolean) $overrideLifetime;
    }

    /**
     * Retrieve whether or not the lifetime of an existing session should be overridden
     *
     * @return boolean
     */
    public function getOverrideLifetime()
    {
        return $this->_overrideLifetime;
    }

    /**
     * Open Session
     *
     * @param string $save_path
     * @param string $name
     * @return boolean
     */
    public function open($savePath, $name)
    {
        $this->_sessionSavePath = $savePath;
        $this->_sessionName     = $name;

        return true;
    }

    /**
     * Close session
     *
     * @return boolean
     */
    public function close()
    {
        return true;
    }

}


// End ^ Native EOL ^ encoding
