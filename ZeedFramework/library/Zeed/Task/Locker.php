<?php
/**
 * Playcool Project
 *
 * LICENSE
 *
 * http://www.playcool.com/license/ice
 *
 * @category   ICE
 * @package    ChangeMe
 * @subpackage ChangeMe
 * @copyright  Copyright (c) 2008 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      2009-11-19
 * @version    SVN: $Id$
 */

/**
 * @author iNewS6
 */
class Zeed_Task_Locker
{
    private $_sock;
    
    /**
     * Lock a port from port list
     *
     * @param String|Integer $ports
     * @return Mixed Locked port,FALSE if locked none
     */
    public function lock($ports, $address = '127.0.0.1')
    {
        $ports = explode(",", $ports);
        $port = 0;
        if (($this->_sock = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
            return false;
        }
        foreach ($ports as $p) {
            if (@socket_bind($this->_sock, $address, (int) $p) === false) {
                continue;
            } else {
                $port = $p;
                break;
            }
        }
        if ($port == 0)
            return false;
        if (@socket_listen($this->_sock, 5) === false) {
            return false;
        }
        return $port;
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

// End ^ LF ^ UTF-8
