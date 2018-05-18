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

class Zeed_Task_Locker_File extends Zeed_Task_Locker_Abstract
{
    /*
     *
     * @var filename 锁定文件路径
     */
    protected $_specificOptions = array(
            'filename' => null);

    private $_filename = null;
    private $_fp = null;
    private $_already_running = false;

    /**
     * 锁定
     *
     * @return string|false 成功返回文件路径
     */
    public function lock()
    {
        $this->_filename = $this->_specificOptions['filename'];

        if (null === $this->_filename) {
            $this->_filename = ZEED_PATH_DATA . '/tmp/Zeed.Task.Locker.pid';
        } else {
            $this->_filename = ZEED_PATH_DATA . '/tmp/' . $this->_filename . '.pid';
        }

        $this->_fp = fopen($this->_filename, 'w+');
        fwrite($this->_fp, getmypid());
        if (! flock($this->_fp, LOCK_EX + LOCK_NB)) {
            $this->_already_running = true;
            fclose($this->_fp);
            return false;
        } else {
            return $this->_filename;
        }
    }

    /**
     * 释放文件锁定
     *
     * @return boolean
     */
    public function unlock()
    {
        if (! $this->_already_running) {
            flock($this->_fp, LOCK_UN);
            fclose($this->_fp);
            unlink($this->_filename);
        }

        return true;
    }
}

// End ^ Native EOL ^ encoding
