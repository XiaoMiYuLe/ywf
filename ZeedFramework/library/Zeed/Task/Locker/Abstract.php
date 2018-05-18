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
 * @since      2010-8-3
 * @version    SVN: $Id$
 */

/**
 * @author Nroe
 */
class Zeed_Task_Locker_Abstract
{
    /**
     * 网关配置
     *
     * @var array $_specificOptions
     */
    protected $_specificOptions = array();

    /**
     * Constructor
     *
     * @param  array $options 支付网关配置
     * @throws Zeed_Exception
     * @return void
     */
    public function __construct($options = array())
    {
        if (! is_array($options)) {
            throw new Zeed_Exception("Options passed were not an array");
        }

        while (list($name, $value) = each($options)) {
            $this->setOption($name, $value);
        }
    }

    /**
     * 设置配置
     *
     * @param  string $name  Name of the option
     * @param  mixed  $value Value of the option
     * @throws Zeed_Exception
     * @return void
     */
    public function setOption($name, $value)
    {
        /**
         * Locker Options
         */
        if (array_key_exists($name, $this->_specificOptions)) {
            // This a specic option of this frontend
            $this->_specificOptions[$name] = $value;
            return;
        }

        throw new Zeed_Exception("Incorrect option name : {$name} at " . __METHOD__);
    }

    public function __destruct()
    {
        $this->unlock();
    }

    /**
     * 锁定
     *
     * @return boolean
     */
    public function lock()
    {
        return false;
    }

    /**
     * 释放锁定
     *
     * @return boolean
     */
    public function unlock()
    {
        return true;
    }
}

// End ^ Native EOL ^ encoding
