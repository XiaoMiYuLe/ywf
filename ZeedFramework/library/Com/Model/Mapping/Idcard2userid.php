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
 * @since      Mar 2, 2011
 * @version    SVN: $Id$
 */

class Com_Model_Mapping_Idcard2userid extends Zeed_Db_Model_Detach
{
    /*
     * @var string The table name.
     */
    protected $_name = 'mapping_idcard2userid';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'userid';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'os_';
    
    /**
     * 定义分表依据字段
     *
     * @var $_detachField string
     */
    protected $_detachField = 'idcard';

    /**
     * 强制跳过 crc32 加密
     *
     * @var boolean
     */
    protected $_skipCrc32 = true;
    
    /**
     * 获取使用指定身份证的用户ID
     * 
     * @param string $idcard
     * @return array
     */
    public function getUseridsByIdcard($idcard, $count = 10000, $offset = 0)
    {
        $this->detachToken($idcard);
        $select = $this->getAdapter()->select()->from($this->getTable())->limit($count, $offset);
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('idcard') . ' = ?', $idcard);
        $select->where($where);
        
        $rows = $select->query()->fetchAll();
        $ids = array();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $row) {
                $ids[] = $row['userid'];
            }
        }
        return $ids;
    }
    
    /**
     * 添加身份证与用户ID映射记录
     * 
     * @param string $idcard
     * @param integer $userid
     * @return boolean
     */
    public function add($idcard, $userid)
    {
        $this->detachToken($idcard);
        $this->insert(array('idcard' => $idcard, 'userid' => $userid));
        return true;
    }
    
    /**
     * 重置身份证
     * 
     * @param string $idcard
     * @param integer $userid
     */
    public function resetIdcard($idcard, $userid)
    {
        $this->detachToken($idcard);
        $name = $this->_name;
        $this->_name = $this->getTable();
        
        $this->delete(array('userid = ?' => $userid));
        
        $this->_name = $name;
        return true;
    }
    
    /**
     * 更新身份证
     * 
     * @param string $idcard
     * @param integer $userid
     * @param string $oldidcard
     * @return boolean
     */
    public function updateIdcard($idcard, $userid, $oldidcard)
    {
        $this->detachToken($oldidcard);
        $name = $this->_name;
        $this->_name = $this->getTable();
        
        $this->delete(array('userid = ?' => $userid));
        $this->_name = $name;
        $this->detachToken($idcard);
        $this->insert(array('idcard' => $idcard, 'userid' => $userid));
        
        $this->_name = $name;
        return true;
    }
    
    /**
     * 分表
     * 将分表条件转成字符串型
     * @see Zeed_Db_Model_Detach::detachToken()
     */
    public function detachToken($idcard)
    {
        parent::detachToken(intval(substr(crc32($idcard), -4)));
    }
    
    /**
     * @return Com_Model_Mapping_Idcard2userid
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}


// End ^ Native EOL ^ encoding
