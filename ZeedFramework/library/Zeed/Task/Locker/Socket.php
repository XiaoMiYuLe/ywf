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

class Zeed_Task_Locker_Socket extends Zeed_Task_Locker_Abstract
{
    /*
     *
     * @var port 端口号
     * @var address 主机地址
     */
    protected $_specificOptions = array(
            'port' => '30070',
            'address' => '127.0.0.1');

    private $_sock;
    private $_port;
    private $_address;

    /**
     * 锁定
     *
     * @return integer|false 成功返回绑定端口号
     */
    public function lock()
    {
        $this->_port = (int) $this->_specificOptions['port'];
        $this->_address = $this->_specificOptions['address'];

        if (($this->_sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            return false;
        }

        if (@socket_bind($this->_sock, $this->_address, $this->_port) === false) {
            return false;
        }

        if (@socket_listen($this->_sock, 5) === false) {
            return false;
        }

        return $this->_port;
    }

    /**
     * Release locker
     */
    public function unlock()
    {
        if ($this->_sock !== false)
            @socket_close($this->_sock);
    }
}

// End ^ Native EOL ^ encoding
