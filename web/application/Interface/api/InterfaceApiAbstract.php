<?php
/**
 * Zeed Platform Project
 * Based on Zeed Framework & Zend Framework.
 *
 * LICENSE
 * http://www.zeed.com.cn/license/
 *
 * @category Zeed
 * @package Zeed_ChangeMe
 * @subpackage ChangeMe
 * @copyright Copyright (c) 2010 Zeed Technologies PRC Inc. (http://www.zeed.com.cn)
 * @author Zeed Team (http://blog.zeed.com.cn)
 * @since 2010-12-6
 * @version SVN: $Id$
 */
class InterfaceApiAbstract extends IndexAbstract
{
    protected $_data = array('status' => 0, 'msg' => '', 'data' => '');
    
    /**
     * 设置错误: $this->_data['msg']...
     *
     * @param int $code 错误码
     * @param string $msg 错误消息
     * @return AdminAbstract
     */
    public function setMsg($code, $msg = null)
    {
        if (is_array($code)) {
            $this->_data['msg'] = $code;
        } elseif (is_string($code)) {
            if (! is_null($msg)) {
                $this->_data['msg'][$code] = $msg;
            } else {
                $this->_data['msg'] = $code;
            }
        }
    
        return $this;
    }
    
    /**
     * Get message.
     *
     * @return array
     */
    public function getMsg()
    {
        return @$this->_data['msg'];
    }
}

// End ^ Native EOL ^ UTF-8