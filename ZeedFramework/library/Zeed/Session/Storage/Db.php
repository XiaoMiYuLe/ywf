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
 * @since      2010-3-8
 * @version    SVN: $Id$
 */

/**
 * Storage session data to SQL Database Server(MySQL/PostgreSQL/MSSQL/Oracle).
 */
class Zeed_Session_Storage_Db extends Zeed_Db_Model implements Zeed_Session_Storage_Interface
{
    
    /*
     * The table name.
     *
     * @var string
     */
    protected $_name = 'session';
    
    public function open($save_path, $name)
    {
    }
    
    /**
     *
     * @param Array $set
     * @param String $where
     * @return Integer
     */
    public function close()
    {
    }
    
    public function read($id)
    {
    }
    
    /**
     *
     * @param Array $set
     * @return Integer
     */
    public function write($id, $data)
    {
    }
    
    public function destroy($id)
    {
    }
    
    public function gc($maxlifetime)
    {
        return true;
    }
    
    public function __construct($config)
    {
    }
    
    public function __destruct()
    {
    }
    
    /**
     * 为啥需要这个函数呢?
     * 这个就是用来获取这个Class的类名
     *
     * @return Zeed_Db_Session
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }

}

// End ^ LF ^ encoding
