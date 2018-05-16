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
 * @since      May 27, 2010
 * @version    SVN: $Id: Error.php 10330 2011-05-16 10:05:06Z xsharp $
 */

/**
 * 
 * 
 * @category   Zeed
 * @package    Zeed_Cas
 * @subpackage models
 * @since      May 27, 2010
 */
class Cas_Model_Login_Error extends Zeed_Db_Model
{
    /**
     * @var string The table name.
     */
    protected $_name = 'login_error';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';
    
    /**
     * @return integer
     */
    public function getRecentCountByUsername($username, $minutes = 20)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('username') . ' = ?', $username);
        $where .= 'AND ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('ctime') . ' >= ?', date(DATETIME_FORMAT, time()-$minutes*60));
        $sql = 'SELECT COUNT(*) AS recentcount FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetchAll();
        unset($sql);
        
        return $row[0]['recentcount'];
    }
    
    /**
     * @return integer
     */
    public function getRecentCountByIpAndUsername($ip, $username, $minutes = 20)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('ip') . ' = ?', $ip);
        $where .= 'AND ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('username') . ' = ?', $username);
        $where .= 'AND ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('ctime') . ' >= ?', date(DATETIME_FORMAT, time()-$minutes*60));
        $sql = 'SELECT COUNT(*) AS recentcount FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetchAll();
        unset($sql);
        
        return $row[0]['recentcount'];
    }
    
    /**
     * @return Cas_Model_Login_Error
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ Native EOL ^ encoding
