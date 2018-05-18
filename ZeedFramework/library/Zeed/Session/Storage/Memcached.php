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
 * @author     xSharp ( GTalk: xSharp@gmail.com )
 * @since      Apr 2, 2010
 * @version    SVN: $Id$
 */

/**
 * Zend_Session_SaveHandler_DbTable
 *
 * @category   Zend
 * @package    Zend_Session
 * @subpackage SaveHandler
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zeed_Session_Storage_Memcached extends Zend_Cache_Backend_Libmemcached implements Zeed_Session_Storage_Interface
{
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
     * Constructor
     *
     * lifetime          => (integer) Session lifetime (optional; default: ini_get('session.gc_maxlifetime'))
     *
     * @param array $config
     * @return void
     * @throws Zeed_Exception
     */
    public function __construct($config)
    {
        if (! is_array($config)) {
            throw new Zeed_Exception('$config must be an array of key/value pairs containing ' . 'configuration options for Zeed_Session_Storage_Memcached.');
        }
        
        foreach ($config as $key => $value) {
            do {
                switch ($key) {
                    case 'lifetime' :
                        $this->setLifetime($value);
                        break;
                    default :
                        // unrecognized options passed to parent::__construct()
                        break 2;
                }
                unset($config[$key]);
            } while (false);
        }
        
        parent::__construct($config);
    }
    
    /**
     * Destructor
     *
     * @return void
     */
    public function __destruct()
    {
        Zeed_Session::writeClose();
    }
    
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
    public function getLifetime($specificLifetime = false)
    {
        return $this->_lifetime;
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
        $this->_sessionName = $name;
        
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
    
    /**
     * Read session data
     *
     * @param string $id
     * @return string
     */
    public function read($id)
    {
        $return = '';
        
        if (false != $data = $this->load($id)) {
            $return = $data;
        }
        
        return $return;
    }
    
    /**
     * Write session data
     *
     * @param string $id
     * @param string $data
     * @return boolean
     */
    public function write($id, $data)
    {
        $return = false;
        
        if ($this->save($data, $id, array(), $this->_lifetime)) {
            $return = true;
        }
        
        return $return;
    }
    
    /**
     * Destroy session
     *
     * @param string $id
     * @return boolean
     */
    public function destroy($id)
    {
        $return = false;
        
        if ($this->remove($id)) {
            $return = true;
        }
        
        return $return;
    }
    
    /**
     * Garbage Collection
     *
     * @param int $maxlifetime
     * @return true
     */
    public function gc($maxlifetime)
    {
        return true;
    }
}

// End ^ LF ^ encoding
