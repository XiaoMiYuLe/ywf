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
 * @since      2010-9-2
 * @version    SVN: $Id$
 */

/**
 * 计数器
 *
 * <code>
 * // 在构造对象时，启动计数器
 * $timer = new Zeed_Util_Timer(true);
 *
 * // 在构造对象时，不启动计数器，使用 start() 函数启动
 * $timer = new Zeed_Util_Timer(false);
 *
 * $timenow = $timer->start();
 * sleep(5);
 * $timenow = $timer->stop();
 *
 * // 获取计数器耗时
 * $timeElapsed = $timer->getElapsedTime();
 *
 * // 重设计数器
 * $timer->reset();
 * </code>
 *
 * @author Nroe
 */
class Zeed_Util_Timer
{
    /**
     * 计数器开启时间
     *
     * @var integer
     */
    protected $_timeStart = 0;
    
    /**
     * 计数器结束时间
     *
     * @var integer
     */
    protected $_timeEnd = 0;
    
    /**
     * 计数器耗时
     *
     * @var integer
     */
    protected $_elapsed = 0;
    
    /**
     * 获取当前时间
     *
     * @return      integer
     */
    protected function _getTime()
    {
        list($usec, $sec) = explode(' ', microtime());
        return ((float) $usec + (float) $sec);
    }
    
    /**
     * 计算当前耗时
     *
     * @return      integer
     */
    protected function _compute()
    {
        if ($this->_timeEnd && $this->_timeStart) {
            return $this->_timeEnd - $this->_timeStart;
        }
        
        return 0;
    }
    
    /**
     * 构造计数器
     *
     * @param       boolean 是否启动计数器
     * @return      void
     */
    public function __construct($start = false)
    {
        if ($start) {
            $this->start();
        }
    }
    
    /**
     * 启动计数器
     *
     * @return      float   $timenow 当前时间
     */
    public function start()
    {
        $this->_timeStart = $this->_getTime();
        
        return $this->_timeStart;
    }
    
    /**
     * 停止计数器
     *
     * @return      float   $timenow 当前时间
     */
    public function stop()
    {
        $this->_timeEnd = $this->_getTime();
        $this->_elapsed = $this->_compute();
        
        return $this->_timeEnd;
    }
    
    /**
     * 获取当前计数器耗时
     *
     * @return      integer
     */
    public function getElapsedTime()
    {
        if (! $this->_elapsed) {
            if ($this->_timeStart) {
                $this->stop();
            }
            
            return 0;
        }
        
        return $this->_elapsed;
    }
    
    /**
     * 重设计数器
     *
     * @return      void
     */
    public function reset()
    {
        $this->_timeStart = 0;
        $this->_timeEnd = 0;
        $this->_elapsed = 0;
    }
}

// End ^ Native EOL ^ encoding
