<?php
/**
 * iNewS Project
 * 
 * LICENSE
 * 
 * http://www.inews.com.cn/license/inews
 * 
 * @category   iNewS
 * @package    ^ChangeMe^
 * @subpackage ^ChangeMe^
 * @copyright Copyright (c) 2009 Zeed Technologies PRC Inc. (http://www.inews.com.cn)
 * @author     Cyrano ( GTalk: cyrano0919@gmail.com )
 * @since      Nov 9, 2010
 * @version    SVN: $$Id$$
 */

class Com_Admin_Model_User extends Com_Admin_Permission_Model
{
    /*
     * @var string The table name.
     */
    protected $_name = 'user';
    
    /**
     * @var integer Primary key.
     */
    protected $_primary = 'username';
    
    /**
     * @var string Table prefix.
     */
    protected $_prefix = 'admin_';
    
    /**
     * 获取用户信息
     * @param string $username
     * @return array
     */
    public function getUserByUsername($username)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('username') . ' = ?', $username);
        $sql = 'SELECT * FROM ' . $this->getTable() . ' WHERE ' . $where;
        $row = $this->getAdapter()->query($sql)->fetch();
        unset($sql);
        return (is_array($row) && count($row) > 0) ? $row : null;
    }
    
    /**
     * 获取用户所属用户组
     * @param string $username
     * @return array
     */
    public function getUserGroupsByUsername($username)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('username') . ' = ?', $username);
        $select = $this->getAdapter()->select()->from('admin_user_group', array('groupid'))->where($where);
        $rows = $select->query()->fetchAll();
        $groups = array();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $row) {
                $groups[] = $row['groupid'];
            }
        }
        return $groups;
    }
    
    /**
     * 获取用户的独立权限，不包含所属组权限
     * @param array|integer $groups
     * @return array
     */
    public function getUserPermissions($username)
    {
        $where = $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('parameter') . ' = ?', strval($username));
        $where .= ' AND ' . $this->getAdapter()->quoteInto($this->getAdapter()->quoteIdentifier('ptype') . ' = ?', 'user');
        
        $select = $this->getAdapter()->select()->from('admin_user_permission', array('permission_id'))->where($where);
        $rows = $select->query()->fetchAll();
        $ps = array();
        if (is_array($rows) && count($rows) > 0) {
            foreach ($rows as $row) {
                $ps[] = $row['permission_id'];
            }
        }
        return $ps;
    }
    
    /**
     * 获取用户的所有权限，包含所属组权限
     * @param string $username
     * @return array
     */
    public function getAllPermissionsOfUser($username)
    {
        $up = $this->getUserPermissions($username);
        $ug = $this->getUserGroupsByUsername($username);
        if (count($ug) > 0) {
            $gp = Com_Admin_Model_Group::instance()->getGroupPermissions($ug);
            $up = array_merge($up, $gp);
        }
        
        return $up;
    }
    
    /**
     * @return Com_Admin_Model_User
     */
    public static function instance()
    {
        return parent::_instance(__CLASS__);
    }
}

// End ^ LF ^ encoding
