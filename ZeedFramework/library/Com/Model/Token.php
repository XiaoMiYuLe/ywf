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
 * @author     Ahdong ( GTalk: ahdong.com@gmail.com )
 * @since      May 5, 2010
 * @version    SVN: $Id: TokenModel.php 5093 2010-05-07 07:16:19Z woody $
 */

class Com_Model_Token extends Zeed_Db_Model
{
    /*
     * The table name.
     * 
     * @var string
     */
    protected $_name = 'token';
    
    /**
     * 主键
     * 
     * @var String
     */
    protected $_primary = 'id';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'cas_';
    
    /**
     * Add a new token
     * 
     * @param array $token
     */
    public function addToken($token)
    {
        if (!isset($token['ctime']) || empty($token['ctime'])) {
            $token['ctime'] = date(DATETIME_FORMAT);
        }
        if (!isset($token['mtime']) || empty($token['mtime'])) {
            $token['mtime'] = date(DATETIME_FORMAT);
        }
        return $this->insert($token);
    }
    
    /**
     * 
     * @param array $token
     * @param integer $tokenid
     * @return integer
     */
    public function updateToken($token, $tokenid)
    {
        if (!isset($token['mtime']) || empty($token['mtime'])) {
            $token['mtime'] = date(DATETIME_FORMAT);
        }
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('id') . ' = ?', $tokenid);
        return $this->update($token,$where);
    }
    
    /**
     * 
     * @param string $tokenKey
     * @return array|null
     */
    public function getTokenByRequestToken($tokenKey)
    {
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('request_token') . ' = ?', $tokenKey);
        $row = $this->getAdapter()->query($sql)->fetchAll();
        unset($sql);
        
        return (is_array($row) && count($row) > 0) ? $row[0] : null;
    }
    
    /**
     * 
     * @param string $tokenKey
     * @return array|null
     */
    public function getTokenByAccessToken($tokenKey)
    {
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('access_token') . ' = ?', $tokenKey);
        $row = $this->getAdapter()->query($sql)->fetchAll();
        unset($sql);
        
        return (is_array($row) && count($row) > 0) ? $row[0] : null;
    }
    
    /**
     * @return TokenModel
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}
